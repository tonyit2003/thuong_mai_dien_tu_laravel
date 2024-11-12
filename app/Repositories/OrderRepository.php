<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use DB;

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

    public function paginationOutOfStock($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {
        $query = $this->model->select($column);
        return $query->keyword($condition['keyword'] ?? null, ['fullname', 'phone', 'email', 'address', 'code'])
            ->publish($condition['publish'] ?? -1)
            ->customDropdownFilter($condition['dropdown'] ?? null)
            ->where(function ($query) use ($condition) {
                $query->orWhere('delivery', '=', 'pending')->orWhere('delivery', '=', 'processing');
            })
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

    public function getOrderByTime($month, $year)
    {
        return $this->model->whereMonth('created_at', $month)->whereYear('created_at', $year)->count();
    }

    public function getTotalOrders()
    {
        return $this->model->count();
    }

    public function getCancelOrders()
    {
        return $this->model->where('confirm', '=', 'cancel')->count();
    }

    public function getRevenueOrders()
    {
        return $this->model->where('delivery', '=', 'success')->where('payment', '=', 'paid')->sum('totalPrice');
    }

    public function getRevenueByYear($year)
    {
        return $this->model->select(
            DB::raw('months.month, COALESCE(SUM(orders.totalPrice), 0) as monthly_revenue')
        )->from(
            DB::raw('
                (
                    SELECT 1 AS month
                    UNION SELECT 2
                    UNION SELECT 3
                    UNION SELECT 4
                    UNION SELECT 5
                    UNION SELECT 6
                    UNION SELECT 7
                    UNION SELECT 8
                    UNION SELECT 9
                    UNION SELECT 10
                    UNION SELECT 11
                    UNION SELECT 12
                ) as months
            ')
        )->leftJoin('orders', function ($join) use ($year) {
            $join->on(DB::raw('months.month'), '=', DB::raw('MONTH(orders.created_at)'))->where('orders.payment', '=', 'paid')->where('orders.delivery', '=', 'success')->where(DB::raw('YEAR(orders.created_at)'), '=', $year);
        })->groupBy('months.month')->orderBy('months.month')->get();
    }

    public function revenue7Day()
    {
        return $this->model->select(DB::raw('
            DATE_FORMAT(dates.date, "%d/%m/%Y") as date,
            COALESCE(SUM(orders.totalPrice), 0) as daily_revenue
        '))->from(DB::raw('
            (
                SELECT CURDATE() - INTERVAL(a.a + (10*b.a) + (100*c.a)) DAY as date
                FROM (
                    SELECT 0 AS a UNION ALL
                    SELECT 1 UNION ALL
                    SELECT 2 UNION ALL
                    SELECT 3 UNION ALL
                    SELECT 4 UNION ALL
                    SELECT 5 UNION ALL
                    SELECT 6 UNION ALL
                    SELECT 7 UNION ALL
                    SELECT 8 UNION ALL
                    SELECT 9
                ) as a
                CROSS JOIN (
                    SELECT 0 AS a UNION ALL
                    SELECT 1 UNION ALL
                    SELECT 2 UNION ALL
                    SELECT 3 UNION ALL
                    SELECT 4 UNION ALL
                    SELECT 5 UNION ALL
                    SELECT 6 UNION ALL
                    SELECT 7 UNION ALL
                    SELECT 8 UNION ALL
                    SELECT 9
                ) as b
                CROSS JOIN (
                    SELECT 0 AS a UNION ALL
                    SELECT 1 UNION ALL
                    SELECT 2 UNION ALL
                    SELECT 3 UNION ALL
                    SELECT 4 UNION ALL
                    SELECT 5 UNION ALL
                    SELECT 6 UNION ALL
                    SELECT 7 UNION ALL
                    SELECT 8 UNION ALL
                    SELECT 9
                ) as c
            ) as dates'))->leftJoin('orders', function ($join) {
            $join->on(DB::raw('DATE(orders.created_at)'), '=', DB::raw('dates.date'))->where('orders.payment', '=', 'paid')->where('orders.delivery', '=', 'success');
        })->where(DB::raw('dates.date'), '>=', DB::raw('CURDATE() - INTERVAL 6 DAY'))->groupBy('dates.date')->orderBy(DB::raw('dates.date'), 'ASC')->get();;
    }

    public function revenueCurrentMonth($currentMonth, $currentYear)
    {
        return $this->model->select(DB::raw('
            DAY(created_at) as day,
            COALESCE(SUM(orders.totalPrice), 0) as daily_revenue
        '))->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->groupBy('day')->orderBy('day')->get();
    }
}
