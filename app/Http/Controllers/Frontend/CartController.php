<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\CartRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\PromotionRepository;
use App\Repositories\ProvinceRepository;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;

class CartController extends FrontendController
{
    protected $provinceRepository;
    protected $promotionRepository;
    protected $customerRepository;
    protected $cartRepository;
    protected $cartService;

    public function __construct(ProvinceRepository $provinceRepository, CustomerRepository $customerRepository, CartRepository $cartRepository, CartService $cartService, PromotionRepository $promotionRepository)
    {
        parent::__construct();
        $this->provinceRepository = $provinceRepository;
        $this->promotionRepository = $promotionRepository;
        $this->customerRepository = $customerRepository;
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
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
        // dd($carts);
        return view('frontend.cart.index', compact('language', 'seo', 'system', 'config', 'provinces', 'customer', 'carts', 'totalPrice', 'cartPromotion'));
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
