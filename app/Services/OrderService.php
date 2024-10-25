<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Services\Interfaces\OrderServiceInterface;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class OrderService implements OrderServiceInterface
{
    protected $productRepository;
    protected $productVariantRepository;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function setInformation($orderProducts = null, $language = 1)
    {
        if (isset($orderProducts) && count($orderProducts)) {
            foreach ($orderProducts as $key => $val) {
                $product = $this->productRepository->findById($val->product_id, ['*'], [
                    'languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }
                ]);
                $productVariant = $this->productVariantRepository->findByCondition([
                    ['uuid', '=', $val->variant_uuid],
                ], false, [
                    'languages' => function ($query) use ($language) {
                        $query->where('language_id', $language);
                    }
                ]);
                $val->name = $product->languages->first()->pivot->name . ' - ' .  $productVariant->languages->first()->pivot->name;
            }
        }
        return $orderProducts;
    }
}
