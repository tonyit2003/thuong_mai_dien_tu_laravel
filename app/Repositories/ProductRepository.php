<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

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
            'products.follow',
            'product_language.name',
            'product_language.description',
            'product_language.content',
            'product_language.meta_title',
            'product_language.meta_keyword',
            'product_language.meta_description',
            'product_language.canonical'
        ])->join('product_language', 'product_language.product_id', '=', 'products.id')->with('product_catalogues')->where('product_language.language_id', '=', $language_id)->find($id);
    }
}