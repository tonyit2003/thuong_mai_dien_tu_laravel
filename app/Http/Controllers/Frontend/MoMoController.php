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

    public function momo_return(Request $request)
    {
        $configMoMo = moMoConfig();

        $secretKey = $configMoMo["secretKey"];

        if (!empty($_GET)) {
            $partnerCode = $_GET["partnerCode"];
            $accessKey = $_GET["accessKey"];
            $orderId = $_GET["orderId"];
            $localMessage = utf8_encode($_GET["localMessage"]);
            $message = $_GET["message"];
            $transId = $_GET["transId"];
            $orderInfo = utf8_encode($_GET["orderInfo"]);
            $amount = $_GET["amount"];
            $errorCode = $_GET["errorCode"];
            $responseTime = $_GET["responseTime"];
            $requestId = $_GET["requestId"];
            $extraData = $_GET["extraData"];
            $payType = $_GET["payType"];
            $orderType = $_GET["orderType"];
            $extraData = $_GET["extraData"];
            $m2signature = $_GET["signature"]; //MoMo signature


            //Checksum
            $rawHash = "partnerCode=" . $partnerCode . "&accessKey=" . $accessKey . "&requestId=" . $requestId . "&amount=" . $amount . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
                "&orderType=" . $orderType . "&transId=" . $transId . "&message=" . $message . "&localMessage=" . $localMessage . "&responseTime=" . $responseTime . "&errorCode=" . $errorCode .
                "&payType=" . $payType . "&extraData=" . $extraData;

            $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

            if ($m2signature == $partnerSignature) {
                if ($errorCode == '0') {
                    // $this->orderService->create($this->language);

                    $code = $request->input('vnp_TxnRef');
                    $language = $this->language;
                    $order = $this->orderRepository->findByCondition([['code', '=', $code]], false, ['products']);
                    $orderProducts = $this->orderProductRepository->findByCondition([['order_id', '=', $order->id]], true);
                    $orderProducts = $this->orderService->setInformation($orderProducts, $language);
                    $system = $this->system;
                    $this->cartService->mail($order, $orderProducts, $system);
                    $config = $this->config();
                    $seo = [
                        'meta_title' => __('info.order_information'),
                        'meta_keyword' => '',
                        'meta_description' => '',
                        'meta_image' => '',
                        'canonical' => write_url('order-information', true, true)
                    ];
                    $template = 'frontend.cart.component.momo';
                    // $this->testVnPayIpn($order, $inputData, $vnp_SecureHash);
                    return view('frontend.cart.success', compact('language', 'seo', 'system', 'config', 'order', 'orderProducts', 'template'));
                } else {
                    return redirect()->route('home.index');
                }
            } else {
                return redirect()->route('home.index');
            }
        } else {
            abort(404);
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
