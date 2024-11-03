<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Services\OrderService;

class OrderController extends FrontendController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function store($orderCode)
    {;
        if ($this->orderService->create($orderCode, $this->language)) {
            flash()->success(__('toast.order_success'));
            return redirect()->route('cart.success', ['code' => $orderCode]);
        }
        flash()->error(__('toast.order_fail'));
        return redirect()->route('cart.checkout');
    }
}
