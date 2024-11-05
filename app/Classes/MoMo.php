<?php

namespace App\Classes;


class MoMo
{
    public function __construct() {}

    public function payment($totalPrice, $orderCode)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $configMoMo = moMoConfig();

        $partnerCode = $configMoMo["partnerCode"];
        $accessKey = $configMoMo["accessKey"];
        $secretKey = $configMoMo["secretKey"];

        $orderInfo = __('info.momo_payment');
        $amount = (string)$totalPrice;
        $redirectUrl = $configMoMo["returnUrl"];
        $ipnUrl = $configMoMo["notifyurl"];

        $bankCode = "";

        $orderId = (string)$orderCode;
        $requestId = time() . "";
        $requestType = "payWithATM";
        $extraData = "";

        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);
        $jsonResult['url'] = $jsonResult['payUrl'] ?? '';
        $jsonResult['errorCode'] = $jsonResult['resultCode'] ?? 1;
        return $jsonResult;
    }
}
