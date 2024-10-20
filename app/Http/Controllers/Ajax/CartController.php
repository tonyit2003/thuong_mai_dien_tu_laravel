<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Http\Request;

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

    public function create(Request $request)
    {
        $flag = $this->cartService->create($request, $this->language);
        return response()->json([
            'messages' => $flag ? __('toast.add_to_cart_success') : __('toast.add_to_cart_failed'),
            'code' => $flag ? 10 : 11,
        ]);
    }

    public function update(Request $request)
    {
        $language = $this->language;
        $get = $request->input();
        $flag = $this->cartService->update($request);
        if ($flag) {
            $carts = $this->cartRepository->findByCondition([
                ['customer_id', '=', $get['customer_id']],
            ], true);
            $carts = $this->cartService->setInformation($carts, $language);
            $cartItem = $carts->first(function ($cart) use ($get) {
                if ($cart->product_id == $get['product_id'] && $cart->variant_uuid == $get['variant_uuid']) {
                    return $cart;
                }
            });
            $totalItem = formatCurrency($this->cartService->getTotalPriceItem($cartItem));
            $totalQuantity = $this->cartService->getTotalQuantity($carts);
            $totalPrice = $this->cartService->getTotalPrice($carts);
            return response()->json([
                'messages' => __('toast.update_quantity_cart_success'),
                'code' => 10,
                'totalItem' => $totalItem,
                'totalPrice' => $totalPrice,
                'totalQuantity' => $totalQuantity,
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
            $carts = $this->cartRepository->findByCondition([
                ['customer_id', '=', $get['customer_id']],
            ], true);
            $carts = $this->cartService->setInformation($carts, $language);
            $totalQuantity = $this->cartService->getTotalQuantity($carts);
            $totalPrice = $this->cartService->getTotalPrice($carts);
            return response()->json([
                'messages' => __('toast.delete_cart_success'),
                'code' => 10,
                'totalPrice' => $totalPrice,
                'totalQuantity' => $totalQuantity,
            ]);
        }
        return response()->json([
            'messages' => __('toast.delete_cart_failed'),
            'code' => 11,
        ]);
    }
}
