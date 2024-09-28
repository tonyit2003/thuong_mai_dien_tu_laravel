<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\Interfaces\ProductVariantRepositoryInterface;
use Illuminate\Support\Facades\DB;

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

    public function updateProductVariantDetails($receiptDetails)
    {
        foreach ($receiptDetails as $detail) {
            $this->model
                ->where('id', $detail->product_variant_id)
                ->update([
                    'quantity' => DB::raw('quantity + ' . $detail->actual_quantity),
                    'price' => $detail->price
                ]);
        }
    }
}
