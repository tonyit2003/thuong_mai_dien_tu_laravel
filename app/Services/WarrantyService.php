<?php

namespace App\Services;

use App\Repositories\WarrantyRepository;
use App\Services\Interfaces\WarrantyServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

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

    // public function paginate($request)
    // {
    //     $condition['keyword'] = addslashes($request->input('keyword'));
    //     $condition['user_catalogue_id'] = $request->input('user_catalogue_id') != null ? $request->input('user_catalogue_id') : 0;
    //     $join = [
    //         ['provinces', 'provinces.code', '=', 'users.province_id'], // Join với bảng provinces
    //         ['districts', 'districts.code', '=', 'users.district_id'], // Join với bảng districts
    //         ['wards', 'wards.code', '=', 'users.ward_id'] // Join với bảng wards
    //     ];
    //     // $request->input('publish') => trả về giá trị, không phải dạng mảng
    //     $condition['publish'] = $request->input('publish') != null ? $request->integer('publish') : -1;
    //     $perPage = $request->input('perpage') != null ? $request->integer('perpage') : 20;
    //     return $this->userRepository->pagination($this->paginateSelect(), $condition, $join, $perPage, ['path' => 'user/index']);
    // }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            // Lấy thông tin từ request
            $productIds = $request->input('product_id', []); // Mảng các sản phẩm được chọn
            $variantUuids = $request->input('variant_uuid', []); // Mảng các variant_uuid tương ứng

            $orderId = (int) $request->input('order_id');
            $warrantyStartDate = now();
            $notes = $request->input('notes', []);
            $status = 'active';

            // Lặp qua từng sản phẩm đã chọn và lưu vào cơ sở dữ liệu dưới dạng các dòng riêng biệt
            foreach ($productIds as $index => $productId) {
                $payload = [
                    'order_id' => $orderId,
                    'product_id' => (int) $productId,
                    'variant_uuid' => $variantUuids[$index] ?? null,
                    'warranty_start_date' => $warrantyStartDate,
                    'notes' => $notes[$index] ?? null,
                    'status' => $status,
                ];
                // Tạo bản ghi mới với dữ liệu đã lọc cho từng sản phẩm
                $this->warrantyRepository->create($payload);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // public function update($id, $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $payload = $request->except('_token', 'send'); // lấy tất cả nhưng ngoại trừ... => trả về dạng mảng
    //         $payload['birthday'] = $this->convertBirthdayDate($payload['birthday']);
    //         $this->userRepository->update($id, $payload);
    //         DB::commit();
    //         return true;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return false;
    //     }
    // }

    // public function delete($id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->userRepository->delete($id);
    //         DB::commit();
    //         return true;
    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return false;
    //     }
    // }

    private function convertBirthdayDate($birthday = '')
    {
        if ($birthday == null) return null;
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday); // input type date trả về dạng Y-m-d
        return $carbonDate->format('Y-m-d H:i:s');
    }

    private function paginateSelect()
    {
        return [
            'users.id',
            'users.name',
            'users.email',
            'users.phone',
            'users.address',
            'users.publish',
            'users.user_catalogue_id',
            'users.province_id',
            'users.district_id',
            'users.ward_id',
        ];
    }
}
