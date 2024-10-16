<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\FrontendController;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends FrontendController
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        parent::__construct();
        $this->cartService = $cartService;
    }

    public function create(Request $request)
    {
        $flag = $this->cartService->create($request, $this->language);
        return response()->json([
            'messages' => $flag ? __('toast.add_to_cart_success') : __('toast.add_to_cart_failed'),
            'code' => $flag ? 10 : 11,
        ]);
    }
}
