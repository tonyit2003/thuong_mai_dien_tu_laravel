<div class="col-lg-3 sidebar">
    <div class="d-flex align-items-center justify-content-start profile-header">
        <img src="storage/{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}" alt="Profile Photo">
        <div class="ml-3">
            <div>{{ $customer->name }}</div>
            <p><strong>{{ __('customerInfo.edit_info') }}</strong></p>
        </div>
    </div>
    <hr>
    <ul class="list-unstyled">
        <li class="no-boder"><img src="frontend/img/icons8-sale-100.png" alt="">
            <a href="#">25.10 Lương Về SaleTo</a>
        </li>
        <li class="no-boder"><img src="frontend/img/icons8-account-100.png" alt="">
            <a href="{{ route('customer.info') }}">{{ __('customerInfo.my_account') }}</a>
        </li>
        <li class="no-boder"><a href="{{ route('customer.info') }}">{{ __('customerInfo.info') }}</a></li>
        <li class="no-boder"><a href="#">{{ __('customerInfo.bank') }}</a></li>
        <li class="no-boder"><a href="{{ route('customer.address') }}">{{ __('customerInfo.address') }}</a></li>
        <li class="no-boder"><a href="{{ route('customer.changePassword') }}">{{ __('customerInfo.change_password') }}</a></li>
        <li class="no-boder"><a href="#">{{ __('customerInfo.notify') }}</a></li>
        <li class="no-boder"><a href="#">{{ __('customerInfo.privacy_settings') }}</a></li>
        <li class="no-boder"><img src="frontend/img/icons8-bill-100.png" alt=""><a href="">{{ __('customerInfo.bill') }}</a></li>
        <li class="no-boder"><img src="frontend/img/icons8-bell-100.png" alt=""><a href="">{{ __('customerInfo.notify') }}</a></li>
        <li class="no-boder"><img src="frontend/img/icons8-card-100.png" alt=""><a href="">{{ __('customerInfo.voucher') }}</a></li>
    </ul>
</div>
