@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])

<div class="order-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="ibox-title-left">
                            <span>{{ __('form.order_detail') }}</span>
                            <span class="badge">
                                <div class="badge__tip"></div>
                                <div class="badge-text">Chưa giao</div>
                            </span>
                        </div>
                        <div class="ibox-title-left">
                            {{ __('form.source_v2') }}: {{ __('form.website') }}
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table-order">
                        <tbody>
                            @foreach ($orderProducts as $key => $val)
                                <tr class="order-item">
                                    <td>
                                        <div class="image">
                                            <span class="image img-scaledown">
                                                <img src="{{ isset($val->image) ? $val->image : 'backend/img/no-photo.png' }}"
                                                    alt="{{ $val->name }}">
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-item-name">
                                            {{ $val->name }}
                                        </div>
                                        {{-- <div class="order-item-voucher">
                                            {{ __('form.discount_code') }}: BBBBBB
                                        </div> --}}
                                    </td>
                                    <td>
                                        <div class="order-item-price">
                                            {{ formatCurrency($val->price) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-item-times">
                                            ✖
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-item-qty">
                                            {{ $val->quantity }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-item-subtotal">
                                            {{ formatCurrency($val->price * $val->quantity) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" class="text-right">{{ __('form.provisional_total') }}</td>
                                <td class="text-right">{{ formatCurrency($order->totalPriceOriginal) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ __('form.discount_v2') }}</td>
                                <td class="text-right" style="color: red">
                                    - {{ formatCurrency($order->promotion['discount'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right">{{ __('form.delivery') }}</td>
                                <td class="text-right">{{ formatCurrency($val->shipping) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-right"><strong>{{ __('form.final_total') }}</strong>
                                </td>
                                <td class="text-right">
                                    <strong>{{ formatCurrency($order->totalPrice) }}</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon">
                                <img src="{{ asset('backend/img/warning.png') }}" alt="">
                            </span>
                            <div class="payment-title">
                                <div class="text_1">
                                    <span class="isConfirm">
                                        {{ __('form.waiting_confirm_order') }}
                                    </span>
                                    20000000
                                </div>
                                <div class="text_2">Thanh toán khi nhận hàng (COD)</div>
                            </div>
                        </div>
                        <div class="cancel-block">
                            {{-- <button class="btn btn-danger">{{ __('button.cancel_order') }}</button> --}}
                        </div>
                    </div>
                </div>
                <div class="payment-confirm">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon">
                                <i class="fa fa-truck"></i>
                            </span>
                            <div class="payment-title">
                                <div class="text_1">
                                    {{ __('form.confirm_order') }}
                                </div>
                            </div>
                        </div>
                        <div class="cancel-block">
                            <button class="btn btn-primary confirm">{{ __('button.confirm') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 order-aside">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>{{ __('form.note') }}</span>
                        <div class="edit span">{{ __('form.edit') }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="description">
                        {{ $order->description }}
                    </div>
                </div>
            </div>
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <span>{{ __('form.customer_information') }}</span>
                        <div class="edit span">{{ __('form.edit') }}</div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="customer-line">
                        <strong>N:</strong> {{ $order->fullname }}
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong> {{ $order->phone }}
                    </div>
                    <div class="customer-line">
                        <strong>E:</strong> {{ $order->email }}
                    </div>
                    <div class="customer-line">
                        <strong>A:</strong> {{ $order->address }}
                    </div>
                    <div class="customer-line">
                        <strong>W:</strong> {{ getAddress(null, null, $order->ward_id, null) }}
                    </div>
                    <div class="customer-line">
                        <strong>D:</strong> {{ getAddress(null, $order->district_id, null, null) }}
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong> {{ getAddress($order->province_id, null, null, null) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
