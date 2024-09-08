<?php

namespace App\Repositories;

use App\Models\ProductReceipt;
use App\Repositories\Interfaces\ProductReceiptRepositoryInterface;

/**
 * Class PostsRepository
 * @package App\Repositories
 */
class ProductReceiptRepository extends BaseRepository implements ProductReceiptRepositoryInterface
{
    protected $model;

    public function __construct(ProductReceipt $productReceipt)
    {
        $this->model = $productReceipt;
        parent::__construct($this->model); //truyền model lên lớp cha
    }

    public function getProductReceiptById($id = 0)
    {
        // Ensure the language ID is properly set
        $languageId = 1/* Your language ID here */;

        return $this->model
            ->with([
                'details.product' => function ($query) use ($languageId) {
                    $query->select('products.id', 'product_language.name as product_name')
                        ->join('product_language', 'products.id', '=', 'product_language.product_id')
                        ->where('product_language.language_id', $languageId);
                },
                'details.productVariant' => function ($query) use ($languageId) {
                    $query->select('product_variants.id', 'product_variant_language.name as variant_name')
                        ->join('product_variant_language', 'product_variants.id', '=', 'product_variant_language.product_variant_id')
                        ->where('product_variant_language.language_id', $languageId);
                }
            ])
            ->select([
                'product_receipts.id',
                'product_receipts.date_created',
                'product_receipts.publish',
                'product_receipts.user_id',
                'product_receipts.total',
                'product_receipts.supplier_id'
            ])
            ->where('product_receipts.id', $id)
            ->first();
    }
}
