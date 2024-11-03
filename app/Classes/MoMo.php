<?php

namespace App\Classes;

use App\Repositories\OrderRepository;

class MoMo
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function payment($order)
    {
        $endpoint = "https://test-payment.momo.vn/gw_payment/transactionProcessor";

        $configMoMo = moMoConfig();

        $partnerCode = $configMoMo["partnerCode"];
        $accessKey = $configMoMo["accessKey"];
        $secretKey = $configMoMo["secretKey"];

        $orderInformation = $this->orderRepository->findByCondition([['code', '=', $order['code']]]);

        $orderInfo = __('info.momo_payment');
        $amount = (string)$orderInformation->totalPrice;
        $returnUrl = $configMoMo["returnUrl"];
        $notifyurl = $configMoMo["notifyurl"];

        $bankCode = "";

        $orderid = $orderInformation->code;
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
        $jsonResult['url'] = $jsonResult['payUrl'];
        return $jsonResult;
    }
}
