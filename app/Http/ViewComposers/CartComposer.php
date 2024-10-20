<?php

namespace App\Http\ViewComposers;

use App\Repositories\CartRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartComposer
{
    protected $cartRepository;
    protected $cartService;

    public function __construct(CartRepository $cartRepository, CartService $cartService)
    {
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
    }

    public function compose(View $view)
    {
        $carts = $this->cartRepository->findByCondition([
            ['customer_id', '=', Auth::guard('customers')->id()],
        ], true);
        if (isset($carts) && count($carts)) {
            $cartTotalQuantity = $this->cartService->getTotalQuantity($carts);
        } else {
            $cartTotalQuantity = 0;
        }
        $view->with('cartTotalQuantity', $cartTotalQuantity);
    }
}
