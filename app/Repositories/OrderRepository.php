<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;

/**
 * Class AttributeCatalogueRepository
 * @package App\Repositories
 */
class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    protected $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
        parent::__construct($this->model);
    }
}
