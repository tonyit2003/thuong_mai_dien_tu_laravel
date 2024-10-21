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
                                @include('frontend.cart.component.information', ['model' => $customer])
                                @include('frontend.cart.component.method')
                                <button type="submit" class="cart-checkout" value="create"
                                    name="create">{{ __('button.order_payment') }}</button>
                            </div>
                        </div>
                        <div class="uk-width-large-2-5">
                            <div class="panel-cart">
                                <div class="panel-head">
                                    <h2 class="cart-heading">
                                        <span>{{ __('info.cart') }}</span>
                                    </h2>
                                </div>
                                @include('frontend.cart.component.item')
                                @include('frontend.cart.component.voucher')
                                @include('frontend.cart.component.summary')
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
