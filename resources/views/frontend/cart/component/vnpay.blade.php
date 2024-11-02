<div class="panel-foot">
    <h2 class="cart-heading">
        <span>{{ __('info.payment_information') }}</span>
    </h2>
    <div class="checkout-box">
        <div class="form-group">
            <label>{{ __('info.order_id') }}:</label>
            <label>{{ $_GET['vnp_TxnRef'] }}</label>
        </div>
        <div class="form-group">
            <label>{{ __('info.total_price') }}:</label>
            <label>{{ formatCurrency($_GET['vnp_Amount'] / 100) }}</label>
        </div>
        <div class="form-group">
            <label>{{ __('info.payment_content') }}:</label>
            <label>{{ $_GET['vnp_OrderInfo'] }}</label>
        </div>
        {{-- <div class="form-group">
            <label>{{ __('info.reponse_code') }} (vnp_ResponseCode):</label>
            <label>{{ $_GET['vnp_ResponseCode'] }}</label>
        </div> --}}
        <div class="form-group">
            <label>{{ __('info.transaction_code_vnpay') }}:</label>
            <label>{{ $_GET['vnp_TransactionNo'] }}</label>
        </div>
        <div class="form-group">
            <label>{{ __('info.bank_code') }}:</label>
            <label>{{ $_GET['vnp_BankCode'] }}</label>
        </div>
        <div class="form-group">
            <label>{{ __('info.payment_time') }}:</label>
            <label>{{ $_GET['vnp_PayDate'] }}</label>
        </div>
        <div class="form-group">
            <label>{{ __('info.result') }}:</label>
            <label>
                @if ($secureHash == $vnp_SecureHash)
                    @if ($_GET['vnp_ResponseCode'] == '00')
                        <span style='color:blue'>{{ __('info.transaction_vnpay_success') }}</span>
                    @else
                        <span style='color:red'>{{ __('info.transaction_vnpay_fail') }}</span>
                    @endif
                @else
                    <span style='color:red'>{{ __('info.invalid_signature') }}</span>
                @endif

            </label>
        </div>
    </div>
</div>
