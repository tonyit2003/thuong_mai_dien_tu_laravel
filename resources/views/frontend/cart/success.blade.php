@extends('frontend.homepage.layout')
@section('content')
    <div class="cart-success">
        <div class="panel-head">
            <h2 class="cart-heading">
                <span>{{ __('info.order_successful') }}</span>
            </h2>
            <div class="discover-text">
                <a href="{{ route('home.index') }}">{{ __('info.explore_more_products') }}</a>
            </div>
        </div>
        <div class="panel-body">
            <h2 class="cart-heading">
                <span>{{ __('info.order_info') }}</span>
            </h2>
            <div class="checkout-box">
                <div class="checkout-box-head">
                    <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                        <div class="uk-width-large-1-3"></div>
                        <div class="uk-width-large-1-3">
                            <div class="order-title uk-text-center">
                                {{ __('info.order_title') }} #{{ $order->code }}
                            </div>
                        </div>
                        <div class="uk-width-large-1-3">
                            <div class="order-date">{{ convertDateTime($order->created_at) }}</div>
                        </div>
                    </div>
                </div>
                <div class="checkout-box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('info.product_name') }}</th>
                                <th>{{ __('info.quantity') }}</th>
                                {{-- <th>{{ __('info.listed_price') }}</th> --}}
                                <th>{{ __('info.selling_price') }}</th>
                                <th>{{ __('info.money') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $initialTotal = 0;
                            @endphp
                            @foreach ($orderProducts as $key => $val)
                                @php
                                    $initialTotal += $val->price * $val->quantity;
                                @endphp
                                <tr>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->quantity }}</td>
                                    {{-- <td>{{ formatCurrency($val->priceOriginal) }}</td> --}}
                                    <td>{{ formatCurrency($val->price) }}</td>
                                    <td><strong>{{ formatCurrency($val->price * $val->quantity) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">{{ __('info.discount_code') }}</td>
                                <td><strong>{{ $order->promotion['code'] ?? '' }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.total_product_value') }}</td>
                                <td><strong>{{ formatCurrency($initialTotal) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.total_promotional_value') }}</td>
                                <td><strong>- {{ formatCurrency($order->promotion['discount'] ?? 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.shipping_fee') }}</td>
                                <td><strong>{{ formatCurrency(0) }}</strong></td>
                            </tr>
                            <tr class="total_payment">
                                <td colspan="3">
                                    <span>{{ __('info.total payment') }}</span>
                                </td>
                                <td><strong>{{ formatCurrency($order->totalPrice) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-foot mb30">
            <h2 class="cart-heading">
                <span>{{ __('info.delivery_information_payment') }}</span>
            </h2>
            <div class="checkout-box">
                <div>
                    {{ __('info.recipient_name') }}: <span>{{ $order->fullname }}</span>
                </div>
                <div>
                    {{ __('info.email') }}: <span>{{ $order->email }}</span>
                </div>
                <div>
                    {{ __('info.address') }}:
                    <span>{{ getAddress($order->province_id, $order->district_id, $order->ward_id, $order->address) }}</span>
                </div>
                <div>
                    {{ __('info.phone') }}: <span>{{ $order->phone }}</span>
                </div>
            </div>
        </div>
        @if (isset($template))
            @include($template)
        @endif
    </div>
@endsection
