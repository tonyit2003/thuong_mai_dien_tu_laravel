<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

/**
 * Class AttributeCatalogueRepository
 * @package App\Repositories
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
        parent::__construct($this->model);
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column);
        return $query->keyword($condition['keyword'] ?? null, ['fullname', 'phone', 'email', 'address', 'code'])
            ->publish($condition['publish'] ?? -1)
            ->customDropdownFilter($condition['dropdown'] ?? null)
            ->customWhere($condition['where'] ?? null)
            ->customWhereRaw($rawQuery['whereRaw'] ?? null)
            ->relationCount($relations ?? null)
            ->relation($relations ?? null)
            ->customJoin($join ?? null)
            ->customGroupBy($extend['groupBy'] ?? null)
            ->customOrderBy($orderBy ?? null)
            ->customCreatedAt($condition['created_at'] ?? null)
            ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
    public function paginationWarranty($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)->where('payment', '=', 'paid');
        return $query->keyword($condition['keyword'] ?? null, ['fullname', 'phone', 'email', 'address', 'code'])
            ->publish($condition['publish'] ?? -1)
            ->customDropdownFilter($condition['dropdown'] ?? null)
            ->customWhere($condition['where'] ?? null)
            ->customWhereRaw($rawQuery['whereRaw'] ?? null)
            ->relationCount($relations ?? null)
            ->relation($relations ?? null)
            ->customJoin($join ?? null)
            ->customGroupBy($extend['groupBy'] ?? null)
            ->customOrderBy($orderBy ?? null)
            ->customCreatedAt($condition['created_at'] ?? null)
            ->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }
}
