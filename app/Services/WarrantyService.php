<?php

namespace App\Services;

use App\Mail\SendWarrantyMail;
use App\Models\Customer;
use App\Repositories\WarrantyRepository;
use App\Services\Interfaces\WarrantyServiceInterface;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mail;

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
            // Lấy thông tin từ request
            $productIds = $request->input('product_id', []); // Mảng các sản phẩm được chọn
            $variantUuids = $request->input('variant_uuid', []); // Mảng các variant_uuid tương ứng
            $orderId = (int) $request->input('order_id');
            $warrantyStartDate = now();
            $warrantyDateOfReceiptList = $request->input('date_of_receipt', []); // Mảng ngày nhận hàng
            $notes = $request->input('notes', []);
            $status = 'active';
            $user_id = Auth::user()->id;

            foreach ($productIds as $index => $productId) {
                $variantUuid = $variantUuids[$index] ?? null;

                // Xử lý ngày nhận hàng tương ứng
                $dateOfReceiptRaw = $warrantyDateOfReceiptList[$index] ?? null;
                $warrantyDateOfReceipt = null;

                if (!empty($dateOfReceiptRaw)) {
                    try {
                        $warrantyDateOfReceipt = Carbon::createFromFormat('Y-m-d', $dateOfReceiptRaw)->format('Y-m-d');
                    } catch (Exception $e) {
                        // Nếu format không đúng, bỏ qua hoặc log lỗi
                        $warrantyDateOfReceipt = null;
                    }
                }

                $payload = [
                    'order_id' => $orderId,
                    'product_id' => (int) $productId,
                    'variant_uuid' => $variantUuid,
                    'warranty_start_date' => $warrantyStartDate,
                    'date_of_receipt' => $warrantyDateOfReceipt,
                    'notes' => $notes[$index] ?? null,
                    'status' => $status,
                    'user_id' => $user_id
                ];
                dd($payload);
                $this->warrantyRepository->create($payload);

                // //Kiểm tra xem bản ghi đã tồn tại chưa
                // $existingWarranty = $this->warrantyRepository->findByOrderAndVariant($orderId, $variantUuid);

                // if ($existingWarranty) {
                //     // Nếu đã tồn tại, cập nhật bản ghi
                //     $payload = [
                //         'warranty_start_date' => $warrantyStartDate,
                //         'date_of_receipt' => $warrantyDateOfReceipt,
                //         'notes' => $notes[$index] ?? $existingWarranty->notes,
                //         'status' => $status,
                //     ];
                //     $this->warrantyRepository->update($existingWarranty->id, $payload);
                // } else {
                //     // Nếu chưa tồn tại, tạo bản ghi mới
                //     $payload = [
                //         'order_id' => $orderId,
                //         'product_id' => (int) $productId,
                //         'variant_uuid' => $variantUuid,
                //         'warranty_start_date' => $warrantyStartDate,
                //         'date_of_receipt' => $warrantyDateOfReceipt,
                //         'notes' => $notes[$index] ?? null,
                //         'status' => $status,
                //     ];
                //     $this->warrantyRepository->create($payload);
                // }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
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
