<?php

namespace App\Classes;

class VNPay
{
    protected $orderRepository;

    public function __construct() {}

    public function payment($totalPrice, $orderCode)
    {
        $configVNPay = vnPayConfig();
        $vnp_Url = $configVNPay['vnp_Url'];
        $vnp_Returnurl = $configVNPay['vnp_Returnurl'];
        $vnp_TmnCode = $configVNPay['vnp_TmnCode'];
        $vnp_HashSecret = $configVNPay['vnp_HashSecret'];

        $vnp_TxnRef = $orderCode;
        $vnp_OrderInfo = __('info.vnpay_payment');
        $vnp_OrderType = '140000';
        $vnp_Amount = $totalPrice * 100;
        $vnp_Locale = 'vn';

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'errorCode' => 0,
            'message' => 'success',
            'url' => $vnp_Url
        );

        return $returnData;
    }
}
