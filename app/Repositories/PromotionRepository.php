<?php

namespace App\Repositories;

use App\Models\Promotion;
use App\Repositories\Interfaces\PromotionRepositoryInterface;

/**
 * Class PromotionsRepository
 * @package App\Repositories
 */
class PromotionRepository extends BaseRepository implements PromotionRepositoryInterface
{
    protected $model;

    public function __construct(Promotion $promotion)
    {
        $this->model = $promotion;
        parent::__construct($this->model);
    }

    // Lấy khuyến mãi lớn nhất của từng sản phẩm
    public function findByProduct($productIds = [])
    {
        return $this->model->selectRaw("
            products.id as product_id,
            MAX(
                IF(
                    promotions.maxDiscountValue != 0,
                    LEAST (
                        CASE
                            WHEN discountType = 'cash' THEN discountValue
                            WHEN discountType = 'percent' THEN products.price * discountValue / 100
                            ELSE 0
                        END,
                        promotions.maxDiscountValue
                    ),
                    CASE
                        WHEN discountType = 'cash' THEN discountValue
                        WHEN discountType = 'percent' THEN products.price * discountValue / 100
                        ELSE 0
                    END
                )
            ) as discount
        ")
            ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
            ->join('products', 'products.id', '=', 'ppv.product_id')
            ->where('products.publish', 1)
            ->where('promotions.publish', 1)
            ->whereIn('products.id', $productIds)
            ->whereDate('promotions.endDate', '>', now())
            ->groupBy('products.id')
            ->get();
    }

    public function findByProductVariant($productVariantIds = [])
    {
        return $this->model->selectRaw("
            pv.id as product_variant_id,
            MAX(
                IF(
                    promotions.maxDiscountValue != 0,
                    LEAST (
                        CASE
                            WHEN discountType = 'cash' THEN discountValue
                            WHEN discountType = 'percent' THEN products.price * discountValue / 100
                            ELSE 0
                        END,
                        promotions.maxDiscountValue
                    ),
                    CASE
                        WHEN discountType = 'cash' THEN discountValue
                        WHEN discountType = 'percent' THEN products.price * discountValue / 100
                        ELSE 0
                    END
                )
            ) as discount
        ")
            ->join('promotion_product_variant as ppv', 'ppv.promotion_id', '=', 'promotions.id')
            ->join('products', 'products.id', '=', 'ppv.product_id')
            ->join('product_variants as pv', 'products.id', '=', 'pv.product_id')
            ->where('products.publish', 1)
            ->where('pv.publish', 1)
            ->where('promotions.publish', 1)
            ->whereIn('pv.id', $productVariantIds)
            ->whereDate('promotions.endDate', '>', now())
            ->groupBy('pv.id')
            ->get();
    }
}

/*
    MAX(promotions.id) as promotion_id,
    MAX(promotions.discountValue) as discountValue,
    MAX(promotions.discountType) as discountType,
    MAX(promotions.maxDiscountValue) as maxDiscountValue,
    MAX(products.id) as product_id,
    MAX(products.price) as price,

    products.id,
    MAX(
        IF(
            promotions.maxDiscountValue != 0,
            LEAST (
                CASE
                    WHEN discountType = 'cash' THEN discountValue
                    WHEN discountType = 'percent' THEN products.price * discountValue / 100
                    ELSE 0
                END,
                promotions.maxDiscountValue
            ),
            CASE
                WHEN discountType = 'cash' THEN discountValue
                WHEN discountType = 'percent' THEN products.price * discountValue / 100
                ELSE 0
            END
        )
    ) as discount
*/
