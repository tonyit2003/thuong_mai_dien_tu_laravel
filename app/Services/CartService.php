<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use App\Repositories\PromotionRepository;
use App\Services\Interfaces\CartServiceInterface;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class CartService implements CartServiceInterface
{
    protected $productRepository;
    protected $productVariantRepository;
    protected $cartRepository;
    protected $promotionRepository;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, CartRepository $cartRepository, PromotionRepository $promotionRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->cartRepository = $cartRepository;
        $this->promotionRepository = $promotionRepository;
    }

    public function create($request, $language = 1)
    {
        DB::beginTransaction();
        try {
            $payload = $request->input();

            $product = $this->productRepository->findById($payload['product_id'], ['*'], [
                'languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                }
            ]);

            $variant = $this->productVariantRepository->findByCondition([
                ['uuid', '=', $payload['variant_uuid']],
            ], false, [
                'languages' => function ($query) use ($language) {
                    $query->where('language_id', $language);
                }
            ]);

            $customer_id = Auth::guard('customers')->id();

            $existingCart = $this->cartRepository->findByCondition([
                ['customer_id', '=', $customer_id],
                ['product_id', '=', $product->id],
                ['variant_uuid', '=', $variant->uuid]
            ]);

            if (isset($existingCart)) {
                $newQuantity = $existingCart->quantity + $payload['quantity'];
                $this->cartRepository->update($existingCart->id, ['quantity' => $newQuantity]);
            } else {
                $data = [
                    'customer_id' => $customer_id,
                    'product_id' => $product->id,
                    'variant_uuid' => $variant->uuid,
                    'quantity' => $payload['quantity'],
                ];
                $this->cartRepository->create($data);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function setInformation($productVariants = null, $language = 1)
    {
        if (isset($productVariants) && count($productVariants)) {
            $productVariantUuids = $productVariants->pluck('variant_uuid')->toArray();
            if (count($productVariantUuids) && isset($productVariantUuids)) {
                $productVariants = $this->combineProductVariantAndPromotion($productVariantUuids, $productVariants);
            }
            foreach ($productVariants as $key => $val) {
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
                if (isset($val->promotions) && $val->promotions->discount > 0) {
                    $val->price = ($productVariant->price - $val->promotions->discount) * $val->quantity;
                } else {
                    $val->price = $productVariant->price * $val->quantity;
                }
                $val->image = isset($productVariant->album) ? explode(',', $productVariant->album)[0] : null;
                $val->name = $product->languages->first()->pivot->name . ' - ' .  $productVariant->languages->first()->pivot->name;
            }
        }
        return $productVariants;
    }

    public function combineProductVariantAndPromotion($productVariantUuids, $productVariants)
    {
        $promotions = $this->promotionRepository->findByProductVariant($productVariantUuids);
        if ($promotions) {
            foreach ($productVariants as $keyProduct => $valProduct) {
                foreach ($promotions as $keyPromotion => $valPromotion) {
                    if ($valPromotion->product_variant_uuid === $valProduct->variant_uuid) {
                        $productVariants[$keyProduct]->promotions = $valPromotion;
                    }
                }
            }
        }
        return $productVariants;
    }

    public function getTotalPrice($carts)
    {
        $total = 0;
        if (isset($carts) && count($carts)) {
            foreach ($carts as $key => $val) {
                $total += $val->price;
            }
        }
        return formatCurrency($total);
    }
}
