<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductVariantRepository extends BaseRepository implements ProductVariantRepositoryInterface
{
    protected $model;

    public function __construct(ProductVariant $productVariant)
    {
        $this->model = $productVariant;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
