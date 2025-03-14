<?php

namespace App\Services;

use App\Classes\Nestedsetbie;
use App\Exports\ProductReceiptExport;
use App\Mail\SendOrderMail;
use App\Repositories\ProductReceiptRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\RouterRepository;
use App\Services\Interfaces\ProductReceiptServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/**
 * Class PostCatalogueService
 * @package App\Services
 */
class ProductReceiptService extends BaseService implements ProductReceiptServiceInterface
{
    protected $productReceiptRepository;
    protected $productVariantRepository;
    protected $nestedset;
    protected $controllerName = 'ProductReceiptController';

    public function __construct(ProductReceiptRepository $productReceiptRepository, ProductVariantRepository $productVariantRepository, RouterRepository $routerRepository)
    {
        $this->productReceiptRepository = $productReceiptRepository;
        $this->productVariantRepository = $productVariantRepository;
        parent::__construct($routerRepository);
    }

    public function statistic()
    {
        $month = now()->month;
        $year = now()->year;
        $previousMonth = ($month == 1) ? 12 : $month - 1;
        $previousYear = ($month == 1) ? $year - 1 : $year;
        $receiptCurrentMonth = $this->productReceiptRepository->getReceiptByTime($month, $year);
        $receiptPreviousMonth = $this->productReceiptRepository->getReceiptByTime($previousMonth, $previousYear);
        return [
            'receiptCurrentMonth' => $receiptCurrentMonth ?? 0,
            // 'orderPreviousMonth' => $orderPreviousMonth ?? 0,
            'growth' => growth($receiptCurrentMonth, $receiptPreviousMonth),
            'totalReceipts' => $this->productReceiptRepository->getTotalReceipts() ?? 0,
            'cancelReceipts' => $this->productReceiptRepository->getCancelReceipts() ?? 0,
            'revenueReceipts' => $this->productReceiptRepository->getRevenueReceipts() ?? 0,
            'totalQuantity' => $this->productReceiptRepository->getTotalQuantity() ?? 0,
            'totalQuantityMonth' => $this->productReceiptRepository->getTotalQuantityMonth() ?? 0,
            'revenueChart' => convertRevenueChartData($this->productReceiptRepository->getRevenueByYear($year), __('dashboard.month'), 'monthly_revenue', 'month'),
        ];
    }

    public function getReceiptChart($request)
    {
        $type = $request->input('charType');
        switch ($type) {
            case 1: {
                    $year = now()->year;
                    $response = convertRevenueChartData($this->productReceiptRepository->getRevenueByYear($year), __('dashboard.month'), 'monthly_revenue', 'month');
                    break;
                }
            case 7: {
                    $response = convertRevenueChartData($this->productReceiptRepository->revenue7Day(), __('dashboard.day'), 'daily_revenue', 'date');
                    break;
                }
            case 30: {
                    $currentMonth = now()->month;
                    $currentYear = now()->year;
                    $daysInMonth = Carbon::createFromDate($currentYear, $currentMonth, 1)->daysInMonth;
                    $allDays = range(1, $daysInMonth);
                    $temp = $this->productReceiptRepository->revenueCurrentMonth($currentMonth, $currentYear);
                    $label = [];
                    $data = [];
                    $temp2 = array_map(function ($day) use ($temp, &$label, &$data) {
                        // lấy phần tử đầu tiên trong $temp mà thỏa mãn điều kiện $record['day'] == $day.
                        $found = collect($temp)->first(function ($record) use ($day) {
                            return $record['day'] == $day;
                        });
                        $label[] = __('dashboard.day') . ' ' . $day;
                        $data[] = isset($found) ? $found['daily_revenue'] : 0;
                    }, $allDays);
                    $response = [
                        'label' => $label,
                        'data' => $data
                    ];
                    break;
                }
        }
        return $response;
    }

    public function mail($email, $productReceipt, $formattedDetails, $system)
    {
        // Tính tổng tiền
        $total = $formattedDetails->reduce(function ($carry, $detail) {
            return $carry + ($detail['price'] * $detail['quantity']);
        }, 0);

        // Tạo file Excel với lớp ProductReceiptExport
        $export = new ProductReceiptExport($formattedDetails, $total, $system, $productReceipt);
        $filePath = $export->export();

        // Dữ liệu cho email
        $data = [
            'formattedDetails' => $formattedDetails,
            'productReceipt' => $productReceipt,
            'system' => $system
        ];

        // Gửi email với file Excel đính kèm
        Mail::to($email)->send(new SendOrderMail($data, file_get_contents($filePath)));
    }

    public function paginate($request, $languageId)
    {
        $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->input('publish') != null ? $request->integer('publish') : -1,
            'date_approved' => $request->input('date_approved'),
            'supplier_id' => $request->input('supplier') != null ? $request->integer('supplier') : 0,
            'user_id' => $request->input('user') != null ? $request->integer('user') : 0,
        ];

        $extend = ['path' => 'receipt/index'];
        return $this->productReceiptRepository->pagination($this->paginateSelect(), $condition, [], $perPage, $extend, []);
    }

    public function create($request, $languageId)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token', 'send');
            $payload['date_created'] = now();
            $payload['user_id'] = Auth::id();
            $payload['supplier_id'] = $request->input('supplier_id') != null ? $request->integer('supplier_id') : 0;
            $payload['total'] = $this->calculateTotal($payload);
            $productReceipt = $this->productReceiptRepository->create($payload);
            $productReceiptId = $productReceipt->id;
            $receiptDetailsPayload = $this->prepareReceiptDetailsPayload($request->all(), $productReceiptId);
            $this->addReceiptDetails($productReceipt, $receiptDetailsPayload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product receipt.'
            ]);
        }
    }

    private function prepareReceiptDetailsPayload($requestData, $productReceiptId)
    {
        $receiptDetails = [];
        $quantities = $requestData['quantityReceipt'] ?? [];
        $prices = $requestData['price'] ?? [];
        $productIds = $requestData['productId'] ?? [];
        $variantIds = $requestData['productVariantId'] ?? [];

        for ($i = 0; $i < count($quantities); $i++) {
            $receiptDetails[] = [
                'product_receipt_id' => $productReceiptId,
                'product_id' => (int) $productIds[$i],
                'product_variant_id' => (int) $variantIds[$i],
                'quantity' => (int) $quantities[$i],
                'price' => convert_price($prices[$i]),
            ];
        }
        return $receiptDetails;
    }

    private function addReceiptDetails($productReceipt, array $receiptDetails)
    {
        foreach ($receiptDetails as $details) {
            $productReceipt->details()->create($details);
        }
    }

    private function calculateTotal($payload)
    {
        $total = 0;
        if (isset($payload['quantityReceipt']) && isset($payload['price'])) {
            for ($i = 0; $i < count($payload['quantityReceipt']); $i++) {
                $quantity = (float)$payload['quantityReceipt'][$i];
                $price = (float)$payload['price'][$i];
                $total += $quantity * $price;
            }
        }

        return $total;
    }

    public function update($id, $request, $languageId)
    {
        DB::beginTransaction();
        try {
            $productReceipt = $this->productReceiptRepository->findById($id);

            $payload = $request->except('_token', 'send');
            $payload['user_id'] = Auth::id();
            $payload['supplier_id'] = $request->input('supplier_id') != null ? $request->integer('supplier_id') : 0;
            $payload['total'] = $this->calculateTotal($payload);

            $productReceipt = $this->productReceiptRepository->update($id, $payload);
            $productReceiptId = $productReceipt->id;
            $productReceipt->details()->delete();

            $receiptDetailsPayload = $this->prepareReceiptDetailsPayload($request->all(), $productReceiptId);
            $this->addReceiptDetails($productReceipt, $receiptDetailsPayload);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function approve($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload['publish'] = 2;
            $payload['date_of_receipt'] = now();
            $payload['date_of_booking'] = now();
            // Xử lý ngày phê duyệt
            if ($request->input('expected_delivery_date')) {
                $payload['expected_delivery_date'] = Carbon::createFromFormat('d/m/Y H:i', $request->input('expected_delivery_date'))->format('Y-m-d H:i:s');
            } else {
                $payload['expected_delivery_date'] = null;
            }
            $this->productReceiptRepository->update($id, $payload);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function calculateActualTotal($payload)
    {
        $total = 0;
        if (isset($payload['actual_quantity']) && isset($payload['price'])) {
            for ($i = 0; $i < count($payload['actual_quantity']); $i++) {
                $quantity = (float)$payload['actual_quantity'][$i];
                $price = (float)$payload['price'][$i];
                $total += $quantity * $price;
            }
        }

        return $total;
    }

    public function delivere($id, $request)
    {
        DB::beginTransaction();
        try {
            // Tìm phiếu nhập hàng theo ID
            $payload = $request->except('_token', 'send');
            $productReceipt = $this->productReceiptRepository->findById($id);
            $payload['publish'] = 3;

            // Xử lý ngày phê duyệt
            if ($request->input('date_approved')) {
                $payload['date_approved'] = Carbon::createFromFormat('d/m/Y H:i', $request->input('date_approved'))->format('Y-m-d H:i:s');
            } else {
                $payload['date_approved'] = null;
            }

            // Lưu số lượng thực nhập
            $actualQuantities = $request->input('actualQuantity', []);
            $prices = $request->input('price', []); // Lấy giá từ request để tính tổng
            // Tính tổng giá trị thực nhập
            $payload['actual_total'] = $this->calculateActualTotal([
                'actual_quantity' => $actualQuantities,
                'price' => $prices
            ]);
            // Cập nhật trạng thái phiếu nhập hàng
            $this->productReceiptRepository->update($id, $payload);

            // Cập nhật số lượng thực nhập cho từng chi tiết
            $receiptDetails = $productReceipt->details;

            foreach ($receiptDetails as $index => $details) {
                // Kiểm tra nếu có số lượng thực nhập cho chi tiết này
                if (isset($actualQuantities[$index])) {
                    $details->actual_quantity = (int) $actualQuantities[$index]; // Gán số lượng thực nhập
                    $details->save(); // Lưu cập nhật vào cơ sở dữ liệu
                }
            }

            // Cập nhật số lượng tồn kho cho từng variant
            $this->productVariantRepository->updateProductVariantDetails($receiptDetails);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->productReceiptRepository->delete($id);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function paginateSelect()
    {
        return [
            'product_receipts.id',
            'product_receipts.publish',
            'product_receipts.user_id',
            'product_receipts.supplier_id',
            'product_receipts.total',
            'product_receipts.actual_total',
            'product_receipts.date_created',
            'product_receipts.date_of_receipt',
            'product_receipts.date_of_booking',
            'product_receipts.date_approved',
            'product_receipts.expected_delivery_date',
        ];
    }
}
