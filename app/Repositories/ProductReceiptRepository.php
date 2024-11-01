<?php

namespace App\Repositories;

use App\Models\ProductReceipt;
use App\Repositories\Interfaces\ProductReceiptRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class PostsRepository
 * @package App\Repositories
 */
class ProductReceiptRepository extends BaseRepository implements ProductReceiptRepositoryInterface
{
    protected $model;

    public function __construct(ProductReceipt $productReceipt)
    {
        $this->model = $productReceipt;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function pagination($column = ['*'], $condition = [], $join = [], $perpage = 20, $extend = [], $relations = [], $orderBy = ['id', 'DESC'], $rawQuery = [])
    {

        $query = $this->model->select($column)->where(function ($query) use ($condition) {
            // Lọc theo trạng thái publish
            if (isset($condition['publish']) && $condition['publish'] != -1) {
                $query->where('publish', '=', $condition['publish']);
            }

            // Lọc theo ngày tạo
            if (isset($condition['date_approved']) && !empty($condition['date_approved'])) {
                // Chuyển đổi chuỗi ngày thành đối tượng Carbon 
                $dateCreated = Carbon::createFromFormat('d/m/Y H:i', $condition['date_approved']);

                // Xác định ngày bắt đầu và ngày kết thúc của tháng
                $startDate = $dateCreated->copy()->startOfMonth(); // Ngày đầu tháng
                $endDate = $dateCreated->copy()->endOfMonth(); // Ngày cuối tháng

                // Sử dụng whereBetween để lọc dữ liệu và cũng lấy những bản ghi có date_approved là null
                $query->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('product_receipts.date_approved', [$startDate, $endDate])
                        ->orWhereNull('product_receipts.date_approved');
                });
            }

            // Lọc theo nhà cung cấp
            if (isset($condition['supplier_id']) && $condition['supplier_id'] != null) {
                $query->where('supplier_id', '=', $condition['supplier_id']);
            }

            // Lọc theo người dùng
            if (isset($condition['user_id']) && $condition['user_id'] != null) {
                $query->where('user_id', '=', $condition['user_id']);
            }

            // // Tìm kiếm theo từ khóa
            if (isset($condition['keyword']) && !empty($condition['keyword'])) {
                $query->where(function ($query) use ($condition) {
                    $query->where('product_receipts.name', 'LIKE', '%' . $condition['keyword'] . '%');
                });
            }
        });

        // Xử lý whereRaw nếu có
        if (isset($rawQuery['whereRaw']) && count($rawQuery['whereRaw'])) {
            foreach ($rawQuery['whereRaw'] as $key => $val) {
                $query->whereRaw($val[0], $val[1]);
            }
        }

        // Xử lý mối quan hệ
        if (isset($relations) && !empty($relations)) {
            foreach ($relations as $relation) {
                $query->withCount($relation);
                $query->with($relation);
            }
        }

        // Xử lý join nếu có
        if (isset($join) && is_array($join) && count($join)) {
            foreach ($join as $key => $val) {
                $query->leftJoin($val[0], $val[1], $val[2], $val[3]);
            }
        }

        // Xử lý sắp xếp
        if (isset($orderBy) && !empty($orderBy)) {
            $query->orderBy($orderBy[0], $orderBy[1]);
        }
        return $query->paginate($perpage)->withQueryString()->withPath(env('APP_URL') . $extend['path']);
    }

    public function getProductReceiptById($id = 0)
    {
        // Ensure the language ID is properly set
        $languageId = 1/* Your language ID here */;

        return $this->model
            ->with([
                'details.product' => function ($query) use ($languageId) {
                    $query->select('products.id', 'product_language.name as product_name')
                        ->join('product_language', 'products.id', '=', 'product_language.product_id')
                        ->where('product_language.language_id', $languageId);
                },
                'details.productVariant' => function ($query) use ($languageId) {
                    $query->select('product_variants.id', 'product_variant_language.name as variant_name')
                        ->join('product_variant_language', 'product_variants.id', '=', 'product_variant_language.product_variant_id')
                        ->where('product_variant_language.language_id', $languageId);
                }
            ])
            ->select([
                'product_receipts.id',
                'product_receipts.date_created',
                'product_receipts.date_of_receipt',
                'product_receipts.date_of_booking',
                'product_receipts.date_approved',
                'product_receipts.expected_delivery_date',
                'product_receipts.publish',
                'product_receipts.user_id',
                'product_receipts.total',
                'product_receipts.actual_total',
                'product_receipts.supplier_id'
            ])
            ->where('product_receipts.id', $id)
            ->first();
    }
}
