<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\WarrantyCard;
use App\Repositories\Interfaces\WarrantyRepositoryInterface;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class WarrantyRepository extends BaseRepository implements WarrantyRepositoryInterface
{
    protected $model;

    public function __construct(WarrantyCard $warrantyCard)
    {
        $this->model = $warrantyCard;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function findByConditionWarranty($condition = [], $flag = false, $relation = [], $param = [], $withCount = [])
    {
        // Khởi tạo một đối tượng truy vấn mới từ model
        $query = $this->model->newQuery();

        // Thêm điều kiện vào truy vấn
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }

        if (isset($param['whereIn'])) {
            $query->whereIn($param['whereInField'], $param['whereIn']);
        }

        $query->with($relation);
        $query->withCount($withCount);

        // Sắp xếp theo warranty_start_date giảm dần và lấy bản ghi đầu tiên để tìm ngày lớn nhất
        $maxDateRecord = $query->orderBy('warranty_start_date', 'DESC')->first();

        if ($maxDateRecord) {
            // Lấy giá trị warranty_start_date lớn nhất
            $maxDate = $maxDateRecord->warranty_start_date;

            // Tạo lại truy vấn với điều kiện thời gian là maxDate
            $query = $this->model->newQuery();

            foreach ($condition as $key => $val) {
                $query->where($val[0], $val[1], $val[2]);
            }

            $query->where('warranty_start_date', '=', $maxDate);
            $query->with($relation);
            $query->withCount($withCount);
        }

        // Trả về tất cả các bản ghi có warranty_start_date lớn nhất
        return $query->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function findByOrderAndVariant($orderId, $variantUuid)
    {
        return $this->model->where('order_id', $orderId)
            ->where('variant_uuid', $variantUuid)
            ->first();
    }
}
