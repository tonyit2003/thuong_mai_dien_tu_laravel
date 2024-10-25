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
}
