<?php

namespace App\Repositories;

use App\Models\ProductReceipt;
use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

/**
 * Class PostsRepository
 * @package App\Repositories
 */
class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    protected $model;

    public function __construct(Supplier $productReceipt)
    {
        $this->model = $productReceipt;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 40, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {

            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('suppliers.name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('suppliers.email', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('suppliers.address', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('suppliers.phone', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
        });

        if (isset($rawQuery['whereRaw']) && count($rawQuery['whereRaw'])) {
            foreach ($rawQuery['whereRaw'] as $key => $val) {
                $query->whereRaw($val[0], $val[1]);
            }
        }

        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
                $query->with($relation);
            }
        }

        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
            }
        }

        if (isset($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
