<?php

namespace App\Repositories;

use App\Models\OrderProduct;
use App\Repositories\Interfaces\OrderProductRepositoryInterface;

/**
 * Class AttributeCatalogueRepository
 * @package App\Repositories
 */
class OrderProductRepository extends BaseRepository implements OrderProductRepositoryInterface
{
    protected $model;

    public function __construct(OrderProduct $orderProduct)
    {
        $this->model = $orderProduct;
        parent::__construct($this->model);
    }

    public function checkProductVariantExists($variant_uuid, $customer_id)
    {
        return $this->model->whereHas('orders', function ($query) use ($customer_id) {
            $query->where('customer_id', $customer_id);
            $query->where('delivery', 'success');
        })->where('variant_uuid', $variant_uuid)->exists();
    }
}
