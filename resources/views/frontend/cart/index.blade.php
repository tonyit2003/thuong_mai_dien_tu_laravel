@extends('frontend.homepage.layout')
@section('content')
    <div class="cart-container">
        <div class="uk-container uk-container-center">
            <form method="post" action="" class="uk-form form">
                @csrf
                <div class="cart-wrapper">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-3-5">
                            <div class="panel-cart cart-left">
                                <div class="panel-head">
                                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                        <h2 class="cart-heading">
                                            <span>{{ __('info.order_information') }}</span>
                                        </h2>
                                        {{-- <span class="has-account">{{ __('info.has_account') }}
                                            <a href="{{ route('authClient.index') }}" title="{{ __('info.login_now') }}">
                                                {{ __('info.login_now') }}
                                            </a>
                                        </span> --}}
                                    </div>
                                </div>
                                <div class="panel-body mb30">
                                    <div class="cart-infomation">
                                        <div class="uk-grid uk-grid-medium mb20">
                                            <div class="uk-width-large-1-2">
                                                <div class="form-row">
                                                    <input type="text" name="fullname" id=""
                                                        value="{{ isset($customer->name) ? $customer->name : '' }}"
                                                        placeholder="{{ __('form.enter_name') }}" class="input-text">
                                                </div>
                                            </div>
                                            <div class="uk-width-large-1-2">
                                                <div class="form-row">
                                                    <input type="text" name="phone" id=""
                                                        value="{{ isset($customer->phone) ? $customer->phone : '' }}"
                                                        placeholder="{{ __('form.enter_phone') }}" class="input-text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mb20">
                                            <input type="text" name="email" id=""
                                                value="{{ isset($customer->email) ? $customer->email : '' }}"
                                                placeholder="{{ __('form.enter_email') }}" class="input-text">
                                        </div>
                                        <div class="uk-grid uk-grid-medium mb20">
                                            <div class="uk-width-large-1-3">
                                                <select name="province_id" id=""
                                                    class="setupSelect2 province location" data-target="district">
                                                    <option value="0">{{ __('form.select_province') }}</option>
                                                    @foreach ($provinces as $key => $val)
                                                        <option @if (old('province_id') == $val->code) selected @endif
                                                            value="{{ $val->code }}">{{ $val->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="district_id" id=""
                                                    class="setupSelect2 district location" data-target="ward">
                                                    <option value="0">{{ __('form.select_district') }}</option>
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="ward_id" id="" class="setupSelect2 ward">
                                                    <option value="0">{{ __('form.select_ward') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb20">
                                            <input type="text" name="address" id=""
                                                value="{{ isset($customer->address) ? $customer->address : '' }}"
                                                placeholder="{{ __('form.enter_address') }}" class="input-text">
                                        </div>
                                        <div class="form-row">
                                            <input type="text" name="description" id="" value=""
                                                placeholder="{{ __('form.note') }}" class="input-text">
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-foot">
                                    <h2 class="cart-heading">
                                        <span>{{ __('form.payment_method') }}</span>
                                    </h2>
                                    <div class="cart-method mb30">
                                        @foreach (__('payment.method') as $key => $val)
                                            <label for="{{ $val['name'] }}" class="uk-flex uk-flex-middle method-item">
                                                <input type="radio" name="method" value="{{ $val['name'] }}"
                                                    {{ $key == 0 ? 'checked' : '' }} id="{{ $val['name'] }}">
                                                <span class="image">
                                                    <img src="{{ asset($val['image']) }}" alt="">
                                                </span>
                                                <span class="title">{{ $val['title'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <button type="submit" class="cart-checkout" value="create"
                                        name="create">{{ __('button.order_payment') }}</button>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-2-5">
                            <div class="panel-cart">
                                <div class="panel-head">
                                    <h2 class="cart-heading">
                                        <span>{{ __('info.cart') }}</span>
                                    </h2>
                                </div>
                                <div class="panel-body">
                                    @if (isset($carts) && count($carts))
                                        <div class="cart-list">
                                            @foreach ($carts as $key => $val)
                                                <div class="cart-item">
                                                    <div class="uk-grid uk-grid-medium">
                                                        <div class="uk-width-small-1-1 uk-width-medium-1-5">
                                                            <div class="cart-item-image">
                                                                <span class="image img-scaledown">
                                                                    <img src="{{ isset($val->image) ? $val->image : 'backend/img/no-photo.png' }}"
                                                                        alt="">
                                                                </span>
                                                                <span class="cart-item-number">{{ $val->quantity }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="uk-width-small-1-1 uk-width-medium-4-5">
                                                            <div class="cart-item-info">
                                                                <h3 class="title">
                                                                    <span>{{ $val->name }}</span>
                                                                </h3>
                                                                <div
                                                                    class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                                                                    <div class="cart-item-qty">
                                                                        <button type="button"
                                                                            class="btn-qty minus">-</button>
                                                                        <input type="text" class="input-qty int"
                                                                            value="{{ $val->quantity }}">
                                                                        <div class="uk-hidden cart-info">
                                                                            <input type="text" class="customer_id"
                                                                                value="{{ $val->customer_id }}">
                                                                            <input type="text" class="product_id"
                                                                                value="{{ $val->product_id }}">
                                                                            <input type="text" class="variant_uuid"
                                                                                value="{{ $val->variant_uuid }}">
                                                                        </div>
                                                                        <button type="button"
                                                                            class="btn-qty plus">+</button>
                                                                    </div>
                                                                    <div class="price cart-item-price"
                                                                        style="margin-bottom: 0px">
                                                                        <div class="cart-price-sale price-sale">
                                                                            {{ formatCurrency($val->price) }}</div>
                                                                    </div>
                                                                    <div class="cart-item-remove"
                                                                        data-customer-id="{{ $val->customer_id }}"
                                                                        data-product-id="{{ $val->product_id }}"
                                                                        data-variant-uuid="{{ $val->variant_uuid }}">
                                                                        <span>✖</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
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
                                        <input type="text" placeholder="{{ __('form.choose_promotion') }}"
                                            name="voucher" value="" readonly id="">
                                        <a href="" class="apply-voucher">{{ __('form.apply') }}</a>
                                    </div>
                                </div>
                                <div class="panel-foot mt30">
                                    <div class="cart-summary">
                                        <div class="cart-summary-item">
                                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                                <span class="summary-title">{{ __('info.discount') }}</span>
                                                <div class="summary-value">- {{ formatCurrency(0) }}</div>
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
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    var province_id = '{{ isset($customer->province_id) ? $customer->province_id : old('province_id') }}';
    var district_id = '{{ isset($customer->district_id) ? $customer->district_id : old('district_id') }}';
    var ward_id = '{{ isset($customer->ward_id) ? $customer->ward_id : old('ward_id') }}';
</script>
