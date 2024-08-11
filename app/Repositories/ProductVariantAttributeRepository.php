<?php

namespace App\Repositories;

use App\Models\ProductVariantAttribute;
use App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductVariantAttributeRepository extends BaseRepository implements ProductVariantAttributeRepositoryInterface
{
    protected $model;

    public function __construct(ProductVariantAttribute $productVariantAttribute)
    {
        $this->model = $productVariantAttribute;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
