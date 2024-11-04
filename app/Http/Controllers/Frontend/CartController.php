<?php

namespace App\Http\Controllers\Frontend;

use App\Classes\MoMo;
use App\Classes\Paypal;
use App\Classes\VNPay;
use App\Http\Controllers\FrontendController;
use App\Http\Requests\StoreCartRequest;
use App\Repositories\CartRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\ProvinceRepository;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class CartController extends FrontendController
{
    protected $provinceRepository;
    protected $promotionRepository;
    protected $customerRepository;
    protected $cartRepository;
    protected $orderRepository;
    protected $orderProductRepository;
    protected $cartService;
    protected $orderService;
    protected $vNPay;
    protected $moMo;
    protected $paypal;

    public function __construct(ProvinceRepository $provinceRepository, CustomerRepository $customerRepository, CartRepository $cartRepository, CartService $cartService, PromotionRepository $promotionRepository, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, OrderService $orderService, VNPay $vNPay, MoMo $moMo, Paypal $paypal)
    {
        parent::__construct();
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->vNPay = $vNPay;
        $this->moMo = $moMo;
        $this->paypal = $paypal;
    }

    public function checkout()
    {
        $language = $this->language;
        $provinces = $this->provinceRepository->all();
        $customer = $this->customerRepository->findById(Auth::guard('customers')->id());
        $carts = $this->cartRepository->findByCondition([
            ['customer_id', '=', Auth::guard('customers')->id()]
        ], true);
        $carts = $this->cartService->setInformation($carts, $language);
        $cartPromotion = $this->cartService->cartPromotion($carts);
        $totalPrice = formatCurrency($this->cartService->getTotalPricePromotion($this->cartService->getTotalPrice($carts), $cartPromotion['discount']));
        $config = $this->config();
        $system = $this->system;
        $seo = [
            'meta_title' => __('info.pay_meta_title'),
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('pay', true, true)
        ];
        return view('frontend.cart.index', compact('language', 'seo', 'system', 'config', 'provinces', 'customer', 'carts', 'totalPrice', 'cartPromotion'));
    }

    public function store(StoreCartRequest $storeCartRequest)
    {
        session()->forget('customer_data');
        session(['customer_data' => $storeCartRequest->except('_token', 'voucher', 'create')]);
        $method = $storeCartRequest->input('method');
        $orderCode = $this->orderService->getOrderCode();
        if ($method != 'cod') {
            $totalPrice = $this->cartService->getTotalPriceByCustomer(Auth::guard('customers')->id(), $this->language);
            $response = $this->paymentOnline($method, $totalPrice, $orderCode);
            if ($response['errorCode'] == 0) {
                // trả về 1 đường dẫn bên ngoài
                return redirect()->away($response['url']);
            } else {
                flash()->error(__('toast.order_fail'));
                return redirect()->route('cart.checkout');
            }
        } else {
            return redirect()->route('order.store', ['code' => $orderCode]);
        }
    }

    public function success($code)
    {
        $language = $this->language;
        $order = $this->orderRepository->findByCondition([['code', '=', $code]], false, ['products']);
        $orderProducts = $this->orderProductRepository->findByCondition([['order_id', '=', $order->id]], true);
        $orderProducts = $this->orderService->setInformation($orderProducts, $language);
        $system = $this->system;
        $this->cartService->mail($order, $orderProducts, $system);
        $config = $this->config();
        $template = $this->getTemplatePayment($order->method);
        $seo = [
            'meta_title' => __('info.order_information'),
            'meta_keyword' => '',
            'meta_description' => '',
            'meta_image' => '',
            'canonical' => write_url('order-information', true, true)
        ];
        return view('frontend.cart.success', compact('language', 'seo', 'system', 'config', 'order', 'orderProducts', 'template'));
    }

    private function getTemplatePayment($method)
    {
        $template = null;
        switch ($method) {
            case 'momo':
                $template = 'frontend.cart.component.momo';
                break;
            case 'vnpay':
                $template = 'frontend.cart.component.vnpay';
                break;
            case 'paypal':
                $template = 'frontend.cart.component.paypal';
                break;
        }
        return $template;
    }

    private function paymentOnline($method, $totalPrice, $orderCode)
    {
        switch ($method) {
            case 'momo':
                $response = $this->moMo->payment($totalPrice, $orderCode);
                break;
            case 'vnpay':
                $response = $this->vNPay->payment($totalPrice, $orderCode);
                break;
            case 'paypal':
                $response = $this->paypal->payment($totalPrice, $orderCode);
                break;
        }
        return $response;
    }

    private function config()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/library/location.js',
                'frontend/core/library/cart.js',
            ]
        ];
    }
}
