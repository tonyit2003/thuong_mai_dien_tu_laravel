<?php

namespace App\Services;

use App\Http\Requests\StoreWarrantyRequest;
use App\Mail\SendWarrantyMail;
use App\Models\Customer;
use App\Repositories\WarrantyRepository;
use App\Services\Interfaces\WarrantyServiceInterface;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Mail;
use Request;

/**
 * Class UserService
 * @package App\Services
 */
class WarrantyService extends BaseService implements WarrantyServiceInterface
{
    protected $warrantyRepository;

    public function __construct(WarrantyRepository $warrantyRepository)
    {
        $this->warrantyRepository = $warrantyRepository;
    }

    public function createOrUpdate($request)
    {
        DB::beginTransaction();
        try {
            $products = $request->input('products', []); // Lấy danh sách sản phẩm từ request
            $orderId = (int)$request->input('order_id');
            $userId = Auth::id();

            foreach ($products as $product) {
                if (isset($product['product_id'])) {
                    $productId = $product['product_id'];
                    $variantUuid = $product['variant_uuid'] ?? null;
                    $notes = $product['notes'] ?? null;
                    $dateOfReceipt = $product['date_of_receipt'] ?? null;

                    // Kiểm tra và định dạng ngày nhận
                    $warrantyDateOfReceipt = null;
                    if ($dateOfReceipt) {
                        try {
                            $warrantyDateOfReceipt = Carbon::createFromFormat('Y-m-d', $dateOfReceipt)->format('Y-m-d');
                        } catch (Exception $e) {
                            $warrantyDateOfReceipt = null;
                        }
                    }

                    // Chuẩn bị dữ liệu để lưu
                    $payload = [
                        'order_id' => $orderId,
                        'product_id' => (int)$productId,
                        'variant_uuid' => $variantUuid,
                        'warranty_start_date' => now(),
                        'date_of_receipt' => $warrantyDateOfReceipt,
                        'notes' => $notes,
                        'status' => 'active',
                        'user_id' => $userId,
                    ];

                    // Lưu vào cơ sở dữ liệu
                    $this->warrantyRepository->create($payload);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Warranty data saved successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error saving warranty data.']);
        }
    }

    public function mail($mailData, $order, $system)
    {
        $customer_email = Customer::find($order->customer_id);
        $to = $customer_email->email;
        $cc = $system['contact_email'];
        $data = [
            'data' => $mailData,
            'customer' => $customer_email,
            'system' => $system,
            'to' => $to
        ];
        Mail::to($to)->cc($cc)->send(new SendWarrantyMail($data));
    }

    public function updateRepair($request)
    {
        DB::beginTransaction();
        try {
            $warrantyIds = array_map('intval', $request->input('id', []));
            $statuses = $request->input('status', []); // Mảng trạng thái từ request
            $completedAt = now(); // Thời gian hoàn thành

            foreach ($warrantyIds as $index => $warrantyId) {
                // Lấy trạng thái hiện tại từ request
                $status = $statuses[$index] ?? 'active';

                // Lấy dữ liệu bảo hành hiện tại từ repository
                $currentWarranty = $this->warrantyRepository->find($warrantyId);

                // Kiểm tra nếu trạng thái là "completed", tăng số lượng sửa chữa
                $quantity = $currentWarranty->quantity ?? 0;
                if ($status === 'completed') {
                    $quantity += 1;
                }

                // Chuẩn bị payload để cập nhật
                $payload = [
                    'status' => $status,
                    'warranty_end_date' => $completedAt,
                    'quantity' => $quantity,
                    'date_of_receipt' => null
                ];

                // Cập nhật bảo hành
                $this->warrantyRepository->update($warrantyId, $payload);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return false;
        }
    }
}
