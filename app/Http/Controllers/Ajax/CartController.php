<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends FrontendController
{
    protected $cartService;
    protected $cartRepository;

    public function __construct(CartService $cartService, CartRepository $cartRepository)
    {
        parent::__construct();
        $this->cartService = $cartService;
        $this->cartRepository = $cartRepository;
    }

    public function checkQuantity(Request $request)
    {
        $flag = $this->cartService->checkQuantity($request);
        return response()->json([
            'messages' => $flag ? '' : __('toast.check_quantity_failed'),
            'code' => $flag ? 10 : 11,
        ]);
    }

    public function create(Request $request)
    {
        $language = $this->language;
        $flag = $this->cartService->create($request, $this->language);
        $carts = $this->cartRepository->findByCondition([
            ['customer_id', '=', Auth::guard('customers')->id()],
        ], true);
        $totalQuantity = $this->cartService->getTotalQuantity($carts);
        return response()->json([
            'messages' => $flag ? __('toast.add_to_cart_success') : __('toast.add_to_cart_failed'),
            'code' => $flag ? 10 : 11,
            'totalQuantity' => $flag ? $totalQuantity : 0,
        ]);
    }

    public function update(Request $request)
    {
        $language = $this->language;
        $get = $request->input();
        $flag = $this->cartService->update($request);
        if ($flag) {
            $carts = $this->getCartByCustomer($get, $language);
            $cartItem = $carts->first(function ($cart) use ($get) {
                if ($cart->product_id == $get['product_id'] && $cart->variant_uuid == $get['variant_uuid']) {
                    return $cart;
                }
            });
            $totalItem = formatCurrency($this->cartService->getTotalPriceItem($cartItem));
            $totalQuantity = $this->cartService->getTotalQuantity($carts);
            $cartPromotion = $this->cartService->cartPromotion($carts);
            $cartDiscount = formatCurrency($cartPromotion['discount']);
            $totalPrice = formatCurrency($this->cartService->getTotalPricePromotion($this->cartService->getTotalPrice($carts), $cartPromotion['discount']));
            return response()->json([
                'messages' => __('toast.update_quantity_cart_success'),
                'code' => 10,
                'totalItem' => $totalItem,
                'cartDiscount' => $cartDiscount,
                'totalPrice' => $totalPrice,
                'totalQuantity' => $totalQuantity ?? 0,
            ]);
        }
        return response()->json([
            'messages' => __('toast.update_quantity_cart_failed'),
            'code' => 11,
        ]);
    }

    public function delete(Request $request)
    {
        $language = $this->language;
        $get = $request->input();
        $flag = $this->cartService->delete($request);
        if ($flag) {
            $carts = $this->getCartByCustomer($get, $language);
            $totalQuantity = $this->cartService->getTotalQuantity($carts);
            $cartPromotion = $this->cartService->cartPromotion($carts);
            $cartDiscount = formatCurrency($cartPromotion['discount']);
            $totalPrice = formatCurrency($this->cartService->getTotalPricePromotion($this->cartService->getTotalPrice($carts), $cartPromotion['discount']));
            return response()->json([
                'messages' => __('toast.delete_cart_success'),
                'code' => 10,
                'cartDiscount' => $cartDiscount,
                'totalPrice' => $totalPrice,
                'totalQuantity' => $totalQuantity ?? 0,
            ]);
        }
        return response()->json([
            'messages' => __('toast.delete_cart_failed'),
            'code' => 11,
        ]);
    }

    private function getCartByCustomer($get, $language)
    {
        $carts = $this->cartRepository->findByCondition([
            ['customer_id', '=', $get['customer_id']],
        ], true);
        $carts = $this->cartService->setInformation($carts, $language);
        return $carts;
    }
}
