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
                                                    <input type="text" name="fullname" id="" value=""
                                                        placeholder="{{ __('form.enter_name') }}" class="input-text">
                                                </div>
                                            </div>
                                            <div class="uk-width-large-1-2">
                                                <div class="form-row">
                                                    <input type="text" name="phone" id="" value=""
                                                        placeholder="{{ __('form.enter_phone') }}" class="input-text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row mb20">
                                            <input type="text" name="email" id="" value=""
                                                placeholder="{{ __('form.enter_email') }}" class="input-text">
                                        </div>
                                        <div class="uk-grid uk-grid-medium mb20">
                                            <div class="uk-width-large-1-3">
                                                <select name="" id="" class="nice-select">
                                                    <option value="">{{ __('form.select_province') }}</option>
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="" id="" class="nice-select">
                                                    <option value="">{{ __('form.select_district') }}</option>
                                                </select>
                                            </div>
                                            <div class="uk-width-large-1-3">
                                                <select name="" id="" class="nice-select">
                                                    <option value="">{{ __('form.select_ward') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb20">
                                            <input type="text" name="address" id="" value=""
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
                                    <div class="cart-list">
                                        @for ($i = 0; $i < 3; $i++)
                                            <div class="cart-item">
                                                <div class="uk-grid uk-grid-medium">
                                                    <div class="uk-width-small-1-1 uk-width-medium-1-5">
                                                        <div class="cart-item-image">
                                                            <span class="image img-scaledown">
                                                                <img src="https://scontent.fsgn5-8.fna.fbcdn.net/v/t39.30808-1/452648975_1553239808882646_747626360851414855_n.jpg?stp=dst-jpg_s200x200&_nc_cat=109&ccb=1-7&_nc_sid=50d2ac&_nc_eui2=AeFYllgEXeffMFjUoPQV7T5G5ejIMbZx-7bl6MgxtnH7tpxY0shu1aYqRYoaKh90xvc7xVx_lxcoEddGISbyS-gq&_nc_ohc=xELauPTXDiUQ7kNvgGX9FuG&_nc_zt=24&_nc_ht=scontent.fsgn5-8.fna&_nc_gid=AfUae7J210rCC86FYosHCxS&oh=00_AYBt5fp3CWtURxrb7OoA4l-Ld0ODnh6SO6tIHCpi4Fy3sA&oe=6719B392"
                                                                    alt="">
                                                            </span>
                                                            <span class="cart-item-number">1</span>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-small-1-1 uk-width-medium-4-5">
                                                        <div class="cart-item-info">
                                                            <h3 class="title">
                                                                <span>AAAAAAAAAAAAAAAAA</span>
                                                            </h3>
                                                            <div
                                                                class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                                                                <div class="cart-item-qty">
                                                                    <button type="button"
                                                                        class="btn-qty minus">-</button>
                                                                    <input type="text" class="input-qty"
                                                                        value="1">
                                                                    <button type="button" class="btn-qty plus">+</button>
                                                                </div>
                                                                <div class="cart-item-price">11.000.000đ</div>
                                                                <div class="cart-item-remove">
                                                                    <span>✖</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="panel-voucher">
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
                                <div class="panel-foot">
                                    <div class="cart-summary">
                                        <div class="cart-summary-item">
                                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                                <span class="summary-title">{{ __('info.discount') }}</span>
                                                <div class="summary-value">-0đ</div>
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
                                                <div class="summary-value cart-total">100.000.000đ</div>
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
