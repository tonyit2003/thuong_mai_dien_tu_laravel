<div class="panel-voucher uk-hidden">
    <div class="voucher-list">
        @for ($i = 0; $i < 10; $i++)
            <div class="voucher-item {{ $i == 0 ? 'active' : '' }}">
                <div class="voucher-left">

                </div>
                <div class="voucher-right">
                    <div class="voucher-title">
                        BBBBBBB
                        <span>(Còn 20)</span>
                    </div>
                    <div class="voucher-description">
                        <p>Khuyến mãi nhân dịp 20/10</p>
                    </div>
                </div>
            </div>
        @endfor
    </div>
    <div class="voucher-form">
        <input type="text" placeholder="{{ __('form.choose_promotion') }}" name="voucher" value="" readonly
            id="">
        <a href="" class="apply-voucher">{{ __('form.apply') }}</a>
    </div>
</div>
