<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderRepository;
use App\Services\CartService;
use App\Services\CustomerService;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoMoController extends FrontendController
{
    protected $orderRepository;
    protected $orderProductRepository;
    protected $orderService;
    protected $cartService;

    public function __construct(OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, OrderService $orderService, CartService $cartService)
    {
        parent::__construct();
        $this->orderRepository = $orderRepository;
        $this->orderProductRepository = $orderProductRepository;
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function momo_return()
    {
        $configMoMo = moMoConfig();

        $secretKey = $configMoMo["secretKey"];
        $partnerCode = $configMoMo["partnerCode"];
        $accessKey = $configMoMo["accessKey"];

        if (!empty($_GET)) {
            $orderId = $_GET["orderId"];
            $message = $_GET["message"];
            $transId = $_GET["transId"];
            $orderInfo = $_GET["orderInfo"];
            $amount = $_GET["amount"];
            $resultCode = $_GET["resultCode"];
            $responseTime = $_GET["responseTime"];
            $requestId = $_GET["requestId"];
            $extraData = $_GET["extraData"];
            $payType = $_GET["payType"];
            $orderType = $_GET["orderType"];
            $extraData = $_GET["extraData"];
            $m2signature = $_GET["signature"]; //MoMo signature


            //Checksum
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&message=" . $message . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&orderType=" . $orderType . "&partnerCode=" . $partnerCode . "&payType=" . $payType . "&requestId=" . $requestId . "&responseTime=" . $responseTime . "&resultCode=" . $resultCode . "&transId=" . $transId;

            $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);


            if ($m2signature == $partnerSignature) {
                if ($resultCode == '0') {
                    return redirect()->route('order.store', ['code' => $orderId]);
                } else {
                    flash()->error(__('info.transaction_momo_fail'));
                    return redirect()->route('cart.checkout');
                }
            } else {
                flash()->error(__('info.transaction_momo_fail'));
                return redirect()->route('cart.checkout');
            }
        }
    }

    public function momo_ipn() {}

    public function testVnPayIpn() {}


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
