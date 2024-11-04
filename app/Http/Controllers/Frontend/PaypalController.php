<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PaypalClient;

class PaypalController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function success(Request $request, $orderCode)
    {
        $provider = new PaypalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()->route('order.store', ['code' => $orderCode]);
        } else {
            flash()->error(__('info.transaction_paypal_fail'));
            return redirect()->route('cart.checkout');
        }
    }

    public function cancel()
    {
        flash()->error(__('info.transaction_paypal_fail'));
        return redirect()->route('cart.checkout');
    }
}
