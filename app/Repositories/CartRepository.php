<?php

namespace App\Repositories;

use App\Models\AttributeCatalogue;
use App\Models\Cart;
use App\Repositories\Interfaces\CartRepositoryInterface;

/**
 * Class AttributeCatalogueRepository
 * @package App\Repositories
 */
class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    protected $model;

    public function __construct(Cart $cart)
    {
        $this->model = $cart;
        parent::__construct($this->model);
    }
}
