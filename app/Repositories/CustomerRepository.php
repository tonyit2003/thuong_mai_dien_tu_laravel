<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

/**
 * Class CustomerRepository
 * @package App\Repositories
 */
class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    protected $model;

    public function __construct(Customer $customer)
    {
        $this->model = $customer;
        parent::__construct($this->model);
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            if (isset($condition['customer_catalogue_id']) && $condition['customer_catalogue_id'] != 0) {
                $query->where('customer_catalogue_id', '=', $condition['customer_catalogue_id']);
            }

            if (isset($condition['source_id']) && $condition['source_id'] != 0) {
                $query->where('source_id', '=', $condition['source_id']);
            }

            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('customers.name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('customers.email', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('customers.address', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('customers.phone', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
        })->with(['customer_catalogues', 'sources']);

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
