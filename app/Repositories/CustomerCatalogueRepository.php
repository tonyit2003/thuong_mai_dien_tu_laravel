<?php

namespace App\Repositories;

use App\Models\CustomerCatalogue;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface;

/**
 * Class CustomerCatalogueRepository
 * @package App\Repositories
 */
class CustomerCatalogueRepository extends BaseRepository implements CustomerCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(CustomerCatalogue $customerCatalogue)
    {
        $this->model = $customerCatalogue;
        parent::__construct($this->model);
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('name', 'LIKE', '%' . $condition['keyword'] . '%')
                        ->orWhere('description', 'LIKE', '%' . $condition['keyword'] . '%');
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
                $query->join($val[0], $val[1], $val[2], $val[3]);
            }
        }

        if (isset($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }

        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
