<?php

namespace App\Repositories;

use App\Models\ProductVariantLanguage;
use App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface;

/**
 * Class ProductsRepository
 * @package App\Repositories
 */
class ProductVariantLanguageRepository extends BaseRepository implements ProductVariantLanguageRepositoryInterface
{
    protected $model;

    public function __construct(ProductVariantLanguage $productVariantLanguage)
    {
        $this->model = $productVariantLanguage;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
