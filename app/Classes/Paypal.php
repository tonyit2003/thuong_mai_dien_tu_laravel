<?php

namespace App\Classes;

use Srmklive\PayPal\Services\PayPal as PaypalClient;


class Paypal
{
    public function __construct() {}

    public function payment($totalPrice, $orderCode)
    {
        $provider = new PaypalClient;
        $accessToken = $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('paypal.success', ['code' => $orderCode]),
                'cancel_url' => route('paypal.cancel'),
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => convertVNDToUSD($totalPrice) ?? 0
                    ],
                ]
            ]
        ];

        $response = $provider->createOrder($data);
        $res['url'] = '';
        if (isset($response['id']) && $response['id'] != '') {
            foreach ($response['links'] as $key => $val) {
                if ($val['rel'] == 'approve') {
                    $res['url'] = $val['href'];
                    $res['errorCode'] = 0;
                }
            }
        }
        return $res;
    }
}
