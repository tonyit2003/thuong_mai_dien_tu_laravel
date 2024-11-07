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

    public function paginationRepairWarranty($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column)
            ->where('payment', '=', 'paid')
            ->whereHas('warranty_cards', function ($q) use ($condition) {
                // Lọc các bảo hành có status = 'active'
                $q->where('status', '=', 'active');
                if (!empty($condition['warranty_code'])) {
                    $q->where('code', '=', $condition['warranty_code']); // Lọc thêm code nếu có
                }
            });

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
