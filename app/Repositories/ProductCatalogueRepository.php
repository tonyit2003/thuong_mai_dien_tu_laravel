<?php

namespace App\Repositories;

use App\Models\ProductCatalogue;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface;

/**
 * Class ProductCatalogueRepository
 * @package App\Repositories
 */
class ProductCatalogueRepository extends BaseRepository implements ProductCatalogueRepositoryInterface
{
    protected $model;

    public function __construct(ProductCatalogue $productCatalogue)
    {
        $this->model = $productCatalogue;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getProductCatalogueById($id = 0, $language_id = 0)
    {
        return $this->model->select([
            'product_catalogues.id',
            'product_catalogues.parent_id',
            'product_catalogues.attribute',
            'product_catalogues.lft',
            'product_catalogues.rgt',
            'product_catalogues.image',
            'product_catalogues.icon',
            'product_catalogues.album',
            'product_catalogues.publish',
            'product_catalogues.follow',
            'product_catalogue_language.name',
            'product_catalogue_language.description',
            'product_catalogue_language.content',
            'product_catalogue_language.meta_title',
            'product_catalogue_language.meta_keyword',
            'product_catalogue_language.meta_description',
            'product_catalogue_language.canonical'
        ])
            ->join('product_catalogue_language', 'product_catalogue_language.product_catalogue_id', '=', 'product_catalogues.id')
            ->where('product_catalogue_language.language_id', '=', $language_id)
            ->find($id);
    }
}
