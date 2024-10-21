<div class="panel-foot mt30">
    <div class="cart-summary">
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summary-title">{{ __('info.discount') }}</span>
                <div class="summary-value discount-value">-
                    {{ formatCurrency($cartPromotion['discount']) }}</div>
            </div>
        </div>
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summary-title">{{ __('info.shipping_fee') }}</span>
                <div class="summary-value">{{ __('info.free') }}</div>
            </div>
        </div>
        <div class="cart-summary-item">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <span class="summary-title bold">{{ __('info.total_price') }}</span>
                <div class="summary-value cart-total">{{ $totalPrice }}</div>
            </div>
        </div>
    </div>
</div>
