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
                                    <td>{{ formatCurrency($val->price * $val->quantity) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">{{ __('info.discount_code') }}</td>
                                <td>{{ $order->promotion['code'] }}</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.total_product_value') }}</td>
                                <td>{{ formatCurrency($initialTotal) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.total_promotional_value') }}</td>
                                <td>- {{ formatCurrency($order->promotion['discount']) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3">{{ __('info.shipping_fee') }}</td>
                                <td>{{ formatCurrency(0) }}</td>
                            </tr>
                            <tr class="total_payment">
                                <td colspan="3">
                                    <span>{{ __('info.total payment') }}</span>
                                </td>
                                <td>{{ formatCurrency($order->totalPrice) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-foot">
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
                    {{ __('info.address') }}: <span>{{ $order->address }}</span>
                </div>
                <div>
                    {{ __('info.phone') }}: <span>{{ $order->phone }}</span>
                </div>
                <div>
                    {{ __('info.payment_method') }}:
                    <span>{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
