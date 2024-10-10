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
    protected $productRepository;

    public function __construct(ProductVariant $productVariant, ProductRepository $productRepository)
    {
        $this->model = $productVariant;
        $this->productRepository = $productRepository;
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
            $this->dataSynchronization($detail);
        }
    }

    private function dataSynchronization($detail)
    {
        $productVariant = $this->findById($detail->product_variant_id);
        $sku = $productVariant->sku;

        $product = $this->productRepository->findById($detail->product_id);
        $variants = json_decode($product->variant);
        foreach ($variants->sku as $key => $val) {
            if ($val === $sku) {
                $variants->price[$key] = $detail->price;
                $variants->quantity[$key] += $detail->actual_quantity;
                break;
            }
        }
        $payload['variant'] = json_encode($variants);
        $this->productRepository->update($detail->product_id, $payload);
    }
}
