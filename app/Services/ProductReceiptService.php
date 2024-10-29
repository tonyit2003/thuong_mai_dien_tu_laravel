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

    public function mail($email, $productReceipt, $formattedDetails, $system)
    {
        $total = $formattedDetails->reduce(function ($carry, $detail) {
            return $carry + ($detail['price'] * $detail['quantity']);
        }, 0);

        // Tạo file Excel trong bộ nhớ
        $excelFile = Excel::raw(new ProductReceiptExport($formattedDetails, $total), \Maatwebsite\Excel\Excel::XLSX);

        // Tạo dữ liệu truyền vào email
        $data = [
            'formattedDetails' => $formattedDetails,
            'productReceipt' => $productReceipt,
            'system' => $system
        ];

        // Gửi email với file Excel đính kèm
        Mail::to($email)->send(new SendOrderMail($data, $excelFile));
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

        $extend = ['path' => 'product/receipt/index'];
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

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $payload['publish'] = 1;
            $payload['date_of_receipt'] = now();
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
        ];
    }
}
