<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Auth;

class OrderController extends FrontendController
{
    protected $orderService;
    protected $customerRepository;
    protected $orderRepository;
    protected $orderProductRepository;

    public function __construct(OrderService $orderService, CustomerRepository $customerRepository, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository)
    {
        parent::__construct();
        $this->orderService = $orderService;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
    }

    public function store($orderCode)
    {
        if ($this->orderService->create($orderCode, $this->language)) {
            flash()->success(__('toast.order_success'));
            return redirect()->route('cart.success', ['code' => $orderCode]);
        }
        flash()->error(__('toast.order_fail'));
        return redirect()->route('cart.checkout');
    }

    public function viewOrder()
    {
        $id = Auth::guard('customers')->user()->id;
        $language = $this->language;
        $system = $this->system;
        $seo = [
            'meta_title' => $system['seo_meta_title'],
            'meta_keyword' => $system['seo_meta_keyword'],
            'meta_description' => $system['seo_meta_description'],
            'meta_image' => $system['seo_meta_image'],
            'canonical' => config('app.url')
        ];
        $customer = $this->customerRepository->findById($id);
        $config = $this->config();

        $orders = $this->orderService->paginateOrderAll($id);

        // Lấy thông tin chi tiết từng hóa đơn
        foreach ($orders as $order) {
            $orderDetails = $this->orderProductRepository->findByCondition(
                [['order_id', '=', $order->id]],
                true
            );
            $order->details = $this->orderService->setInformation($orderDetails, $language);
            $order = $this->orderService->setAddress($order);
        }

        return view('frontend.order.index', compact('config', 'language', 'system', 'seo', 'customer', 'orders'));
    }

    private function config()
    {
        return [
            'css' => [
                'frontend/css/customer.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'backend/css/bootstrap.min.css',
                'frontend/css/order.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/location.js',
                'backend/js/bootstrap.min.js',
                'frontend/core/library/cart.js',
                'https://code.jquery.com/jquery-3.5.1.slim.min.js'
            ],
        ];
    }
}
