<?php

namespace App\Repositories;

use App\Models\ProductReceipt;
use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

/**
 * Class PostsRepository
 * @package App\Repositories
 */
class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    protected $model;

    public function __construct(Supplier $productReceipt)
    {
        $this->model = $productReceipt;
        parent::__construct($this->model); //truyền model lên lớp cha
    }
}
