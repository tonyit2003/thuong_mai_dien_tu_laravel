<?php

namespace App\Services\Interfaces;

/**
 * Interface ProductServiceInterface
 * @package App\Services\Interfaces
 */
interface ProductVariantServiceInterface
{
    public function paginate($request, $languageId, $productCatalogue = null, $extend = [], $page = 1);
}
