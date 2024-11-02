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

class VNPayController extends FrontendController
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

    public function vnpay_return(Request $request)
    {
        $configVNPay = vnPayConfig();

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $vnp_TmnCode = $configVNPay['vnp_TmnCode'];
        $vnp_HashSecret = $configVNPay['vnp_HashSecret'];
        $vnp_Url = $configVNPay['vnp_Url'];
        $vnp_Returnurl = $configVNPay['vnp_Returnurl'];
        $vnp_apiUrl = $configVNPay['vnp_apiUrl'];
        $apiUrl = $configVNPay['apiUrl'];

        $vnp_SecureHash = $_GET['vnp_SecureHash'];
        $inputData = array();
        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        if ($secureHash == $vnp_SecureHash) {
            if ($_GET['vnp_ResponseCode'] == '00') {
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
                $template = 'frontend.cart.component.vnpay';
                $this->testVnPayIpn($order, $inputData, $vnp_SecureHash);
                return view('frontend.cart.success', compact('language', 'seo', 'system', 'config', 'order', 'orderProducts', 'template', 'secureHash', 'vnp_SecureHash'));
            } else {
                echo __('info.transaction_vnpay_fail');
            }
        } else {
            echo __('info.invalid_signature');
        }
    }

    public function vnpay_ipn()
    {
        $configVNPay = vnPayConfig();

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $vnp_TmnCode = $configVNPay['vnp_TmnCode'];
        $vnp_HashSecret = $configVNPay['vnp_HashSecret'];
        $vnp_Url = $configVNPay['vnp_Url'];
        $vnp_Returnurl = $configVNPay['vnp_Returnurl'];
        $vnp_apiUrl = $configVNPay['vnp_apiUrl'];
        $apiUrl = $configVNPay['apiUrl'];

        $inputData = array();
        $returnData = array();

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo'];
        $vnp_BankCode = $inputData['vnp_BankCode'];
        $vnp_Amount = $inputData['vnp_Amount'] / 100;

        $Status = 0;
        $orderId = $inputData['vnp_TxnRef'];

        try {
            if ($secureHash == $vnp_SecureHash) {
                $order = $this->orderRepository->findByCondition([['code', '=', $orderId]], false, ['products']);
                if ($order != NULL) {
                    if ($order->totalPrice == $vnp_Amount) {
                        if ($order->payment != NULL && $order->payment == 'unpaid') {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $payload['payment'] = 'paid';
                                $this->orderService->updateVNPay($payload, $order);
                            } else {
                                $payload['payment'] = 'unpaid';
                                $this->orderService->updateVNPay($payload, $order);
                            }
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        // echo json_encode($returnData);
    }

    public function testVnPayIpn($order, $inputData, $secureHash)
    {
        $configVNPay = vnPayConfig();

        $startTime = date("YmdHis");
        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));
        $vnp_TmnCode = $configVNPay['vnp_TmnCode'];
        $vnp_HashSecret = $configVNPay['vnp_HashSecret'];
        $vnp_Url = $configVNPay['vnp_Url'];
        $vnp_Returnurl = $configVNPay['vnp_Returnurl'];
        $vnp_apiUrl = $configVNPay['vnp_apiUrl'];
        $apiUrl = $configVNPay['apiUrl'];

        $returnData = array();

        $vnp_SecureHash = $secureHash;
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $vnpTranId = $inputData['vnp_TransactionNo'];
        $vnp_BankCode = $inputData['vnp_BankCode'];
        $vnp_Amount = $inputData['vnp_Amount'] / 100;

        try {
            if ($secureHash == $vnp_SecureHash) {

                if ($order != NULL) {
                    if ($order->totalPrice == $vnp_Amount) {
                        if ($order->payment != NULL && $order->payment == 'unpaid') {
                            if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                                $payload['payment'] = 'paid';
                                $this->orderService->updateVNPay($payload, $order);
                            } else {
                                $payload['payment'] = 'unpaid';
                                $this->orderService->updateVNPay($payload, $order);
                            }
                            $returnData['RspCode'] = '00';
                            $returnData['Message'] = 'Confirm Success';
                        } else {
                            $returnData['RspCode'] = '02';
                            $returnData['Message'] = 'Order already confirmed';
                        }
                    } else {
                        $returnData['RspCode'] = '04';
                        $returnData['Message'] = 'invalid amount';
                    }
                } else {
                    $returnData['RspCode'] = '01';
                    $returnData['Message'] = 'Order not found';
                }
            } else {
                $returnData['RspCode'] = '97';
                $returnData['Message'] = 'Invalid signature';
            }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
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
