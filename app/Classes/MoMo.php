<?php

namespace App\Classes;


class MoMo
{
    public function __construct() {}

    public function payment($totalPrice, $orderCode)
    {
        $endpoint = "https://test-payment.momo.vn/gw_payment/transactionProcessor";

        $configMoMo = moMoConfig();

        $partnerCode = $configMoMo["partnerCode"];
        $accessKey = $configMoMo["accessKey"];
        $secretKey = $configMoMo["secretKey"];

        $orderInfo = __('info.momo_payment');
        $amount = (string)$totalPrice;
        $returnUrl = $configMoMo["returnUrl"];
        $notifyurl = $configMoMo["notifyurl"];

        $bankCode = "";

        $orderid = (string)$orderCode;
        $requestId = time() . "";
        $requestType = "payWithMoMoATM";
        $extraData = "";
        $rawHashArr =  array(
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderid,
            'orderInfo' => $orderInfo,
            'bankCode' => $bankCode,
            'returnUrl' => $returnUrl,
            'notifyUrl' => $notifyurl,
            'extraData' => $extraData,
            'requestType' => $requestType
        );
        $rawHash = "partnerCode=" . $partnerCode . "&accessKey=" . $accessKey . "&requestId=" . $requestId . "&bankCode=" . $bankCode . "&amount=" . $amount . "&orderId=" . $orderid . "&orderInfo=" . $orderInfo . "&returnUrl=" . $returnUrl . "&notifyUrl=" . $notifyurl . "&extraData=" . $extraData . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data =  array(
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderid,
            'orderInfo' => $orderInfo,
            'returnUrl' => $returnUrl,
            'bankCode' => $bankCode,
            'notifyUrl' => $notifyurl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        $jsonResult['url'] = $jsonResult['payUrl'] ?? '';
        return $jsonResult;
    }
}
