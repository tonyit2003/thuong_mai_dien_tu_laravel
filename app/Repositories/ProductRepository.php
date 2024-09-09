<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $product)
    {
        $this->model = $product;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getProductById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'products.id',
            'products.product_catalogue_id',
            'products.image',
            'products.icon',
            'products.album',
            'products.publish',
            'products.price',
            'products.code',
            'products.made_in',
            'products.attributeCatalogue',
            'products.attribute',
            'products.variant',
            'product_language.name',
            'product_language.description',
            'product_language.content',
            'product_language.meta_title',
            'product_language.meta_keyword',
            'product_language.meta_description',
            'product_language.canonical'
        ])->join('product_language', 'product_language.product_id', '=', 'products.id')->with([
            'product_catalogues',
            'product_variants' => function ($query) use ($language_id) {
                $query->with(['attributes' => function ($query) use ($language_id) {
                    $query->with(['attribute_language' => function ($query) use ($language_id) {
                        $query->where('language_id', '=', $language_id);
                    }]);
                }]);
            }
        ])->where('product_language.language_id', '=', $language_id)->find($id);
    }

    public function findProductForPromotion($condition = [], $relation = [])
    {
        $query = $this->model->newQuery();
        $query->select([
            'products.id',
            'products.image',
            'product_language.name',
            'product_variants.id as product_variant_id',
            // DB::raw: truyền một đoạn SQL trực tiếp vào truy vấn mà không qua Eloquent để Laravel không xử lý hay thoát chuỗi SQL này.
            // CONCAT: nối chuỗi các cột lại với nhau.
            // COALESCE: trả về giá trị của cột product_variant_language.name nếu nó không phải NULL. Nếu cột này là NULL, nó sẽ trả về "default".
            DB::raw('CONCAT(product_language.name, " - ", COALESCE(product_variant_language.name, "Default")) as variant_name')
        ]);
        $query->join('product_language', 'products.id', '=', 'product_language.product_id');
        $query->leftJoin('product_variants', 'products.id', '=', 'product_variants.product_id');
        $query->leftJoin('product_variant_language', 'product_variants.id', '=', 'product_variant_language.product_variant_id');
        foreach ($condition as $key => $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
        if (count($relation)) {
            $query->with($relation);
        }
        $query->orderBy('id', 'DESC');
        return $query->get();
    }
}
