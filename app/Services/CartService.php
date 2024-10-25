<?php

namespace App\Services;

use App\Enums\OrderEnum;
use App\Enums\PromotionEnum;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
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
    protected $orderRepository;

    public function __construct(ProductRepository $productRepository, ProductVariantRepository $productVariantRepository, CartRepository $cartRepository, PromotionRepository $promotionRepository, OrderRepository $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->cartRepository = $cartRepository;
        $this->promotionRepository = $promotionRepository;
        $this->orderRepository = $orderRepository;
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

    public function update($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token');
            $this->cartRepository->updateByWhere([
                ['customer_id', '=', $payload['customer_id']],
                ['product_id', '=', $payload['product_id']],
                ['variant_uuid', '=', $payload['variant_uuid']],
            ], ['quantity' => $payload['quantity']]);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function delete($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token');
            $this->cartRepository->deleteByCondition([
                ['customer_id', '=', $payload['customer_id']],
                ['product_id', '=', $payload['product_id']],
                ['variant_uuid', '=', $payload['variant_uuid']],
            ]);
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
                    $val->priceUnit = ($productVariant->price - $val->promotions->discount);
                } else {
                    $val->priceUnit = $productVariant->price;
                }
                $val->price = $this->getTotalPriceItem($val);
                $val->image = isset($productVariant->album) ? explode(',', $productVariant->album)[0] : null;
                $val->name = $product->languages->first()->pivot->name . ' - ' .  $productVariant->languages->first()->pivot->name;
            }
        }
        return $productVariants;
    }

    public function getTotalPriceItem($cart)
    {
        $productVariant = $this->productVariantRepository->findByCondition([
            ['uuid', '=', $cart->variant_uuid],
        ]);
        $price = 0;
        if (isset($cart->promotions) && $cart->promotions->discount > 0) {
            $price = ($productVariant->price - $cart->promotions->discount) * $cart->quantity;
        } else {
            $price = $productVariant->price * $cart->quantity;
        }
        return $price;
    }

    public function combineProductVariantAndPromotion($productVariantUuids, $productVariants, $flag = false)
    {
        $promotions = $this->promotionRepository->findByProductVariant($productVariantUuids);
        if ($promotions) {
            if ($flag == true) {
                $productVariants->promotions = $promotions[0] ?? [];
                return $productVariants;
            }
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
        return $total;
    }

    public function getTotalQuantity($carts)
    {
        $quantity = 0;
        if (isset($carts) && count($carts)) {
            foreach ($carts as $key => $val) {
                $quantity += $val->quantity;
            }
        }
        return $quantity;
    }

    public function cartPromotion($carts)
    {
        $maxDiscount = 0;
        $selectedPromotion = null;
        if (isset($carts)) {
            $cartTotal = $this->getTotalPrice($carts);
            $promotions = $this->promotionRepository->getPromotionByCartTotal();
            if (isset($promotions)) {
                foreach ($promotions as $promotion) {
                    $discount = $promotion->discountInformation['info'];
                    $amountFrom = $discount['amountFrom'] ?? [];
                    $amountTo = $discount['amountTo'] ?? [];
                    $amountValue = $discount['amountValue'] ?? [];
                    $amountType = $discount['amountType'] ?? [];

                    if (isset($amountFrom) && count($amountFrom) == count($amountTo) && count($amountTo) == count($amountValue)) {
                        for ($i = 0; $i < count($amountFrom); $i++) {
                            $currentAmountFrom = convert_price($amountFrom[$i]);
                            $currentAmountTo = convert_price($amountTo[$i]);
                            $currentAmountValue = convert_price($amountValue[$i]);
                            $currentAmountType = $amountType[$i];

                            if ($cartTotal >= $currentAmountFrom && $cartTotal <= $currentAmountTo) {
                                if ($currentAmountType == PromotionEnum::CASH) {
                                    $maxDiscount = max($maxDiscount, $currentAmountValue);
                                } else if ($currentAmountType == PromotionEnum::PERCENT) {
                                    $maxDiscount = max($maxDiscount, ($currentAmountValue / 100) * $cartTotal);
                                }
                                $selectedPromotion = $promotion;
                            }
                        }
                    }
                }
            }
        }
        return [
            'discount' => $maxDiscount,
            'promotion' => $selectedPromotion
        ];
    }

    public function getTotalPricePromotion($totalPrice = 0, $discount = 0)
    {
        if ($totalPrice < $discount) {
            return 0;
        }
        return $totalPrice - $discount;
    }

    public function order($request, $language)
    {
        DB::beginTransaction();
        try {
            $carts = $this->cartRepository->findByCondition([
                ['customer_id', '=', Auth::guard('customers')->id()]
            ], true);
            $carts = $this->setInformation($carts, $language);
            $cartPromotion = $this->cartPromotion($carts);
            $totalPrice = $this->getTotalPricePromotion($this->getTotalPrice($carts), $cartPromotion['discount']);

            $payload = $this->request($request, $cartPromotion, $totalPrice);
            $order = $this->orderRepository->create($payload);
            if ($order->id > 0) {
                $code = $this->updateOrderCode($order);
                $this->createOrderProduct($order, $carts);
                $this->paymentOnline($payload['method']);
                $this->cartRepository->deleteByCondition([
                    ['customer_id', '=', Auth::guard('customers')->id()],
                ]);
            }
            DB::commit();
            return [
                'code' => $code,
                'flag' => true
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'code' => null,
                'flag' => false
            ];
        }
    }

    private function request($request, $cartPromotion, $totalPrice)
    {
        $payload = $request->except('_token', 'voucher', 'create');
        $payload['customer_id'] = Auth::guard('customers')->id();
        if (isset($cartPromotion['promotion'])) {
            $payload['promotion']['discount'] = $cartPromotion['discount'];
            $payload['promotion']['name'] = $cartPromotion['promotion']->name;
            $payload['promotion']['code'] = $cartPromotion['promotion']->code;
            $payload['promotion']['startDate'] = $cartPromotion['promotion']->startDate;
            $payload['promotion']['endDate'] = $cartPromotion['promotion']->endDate;
        }
        $payload['totalPrice'] = $totalPrice;
        $payload['confirm'] = 'pending';
        $payload['delivery'] = 'pending';
        $payload['payment'] = 'unpaid';

        return $payload;
    }

    private function updateOrderCode($order)
    {
        $payload['code'] =  Auth::guard('customers')->id() . '-' . (OrderEnum::ORDER_CODE + $order->id);
        $this->orderRepository->update($order->id, $payload);
        return $payload['code'];
    }

    private function createOrderProduct($order, $carts)
    {
        $payload = [];
        if (isset($carts)) {
            foreach ($carts as $key => $val) {
                $payload[$val->product_id] = [
                    'variant_uuid' => $val->variant_uuid,
                    'quantity' => $val->quantity,
                    'price' => $val->priceUnit,
                    'priceOriginal' => $this->productVariantRepository->findByCondition([['uuid', '=', $val->variant_uuid]])->price,
                ];
            }
        }
        // đồng bộ dữ liệu bảng trung gian = $payload dựa trên 2 khóa ngoại
        $order->products()->sync($payload);
    }

    private function paymentOnline($method)
    {
        switch ($method) {
            case 'zalo':
                // $this->zaloPay();
                break;
            case 'momo':
                // $this->momo();
                break;
            case 'shoppe':
                // $this->shoppePay();
                break;
            case 'vnpay':
                // $this->vnPay();
                break;
            case 'paypal':
                // $this->paypal();
                break;
        }
    }
}
