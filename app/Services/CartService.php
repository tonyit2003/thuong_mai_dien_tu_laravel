<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
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

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, CartRepository $cartRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->cartRepository = $cartRepository;
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
                    'name' => $product->languages->first()->pivot->name . ' - ' .  $variant->languages->first()->pivot->name,
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
}
