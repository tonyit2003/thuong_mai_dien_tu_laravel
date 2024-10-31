@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])

<div class="order-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="ibox-title-left">
                            <div class="mb15" style="font-weight: bold">
                                <span>{{ __('form.order_detail') }} #{{ $order->code }}</span>
                            </div>
                            <div class="uk-flex uk-flex-middle uk-flex-space-between mb15">
                                <div> - {{ __('form.status_delivery') }}: </div>
                                <span class="badge">
                                    <div class="badge__tip"></div>
                                    <div class="badge-text">
                                        {{ __('statusOrder.delivery')[$order->delivery] }}
                                    </div>
                                </span>
                            </div>
                            <div class="uk-flex uk-flex-middle">
                                <div> - {{ __('form.status_payment') }}: </div>
                                <span class="badge">
                                    <div class="badge__tip"></div>
                                    <div class="badge-text">
                                        {{ __('statusOrder.payment')[$order->payment] }}
                                    </div>
                                </span>
                            </div>
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
                <div class="payment-confirm confirm-box">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <div class="uk-flex uk-flex-middle">
                            <span class="icon">
                                <img src="{{ $order->confirm == 'pending' ? asset('backend/img/warning.png') : ($order->confirm == 'confirm' ? asset('backend/img/check.png') : asset('backend/img/remove.png')) }}"
                                    alt="">
                            </span>
                            <div class="payment-title">
                                <div class="text_1">
                                    <span class="isConfirm">
                                        {{ __('order.confirm-title')[$order->confirm] }}
                                    </span>
                                    {{-- {{ formatCurrency($order->totalPrice) }} --}}
                                </div>
                                <div class="text_2">
                                    {{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="cancel-block {{ $order->confirm == 'cancel' ? 'text-danger' : '' }}">
                            {!! $order->confirm == 'confirm'
                                ? '<button class="btn btn-danger updateField" data-field="confirm" data-value="cancel" data-title="' .
                                    __('form.order_canceled') .
                                    '">' .
                                    __('button.cancel_order') .
                                    '</button>'
                                : ($order->confirm == 'cancel'
                                    ? __('form.canceled')
                                    : '') !!}
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
                        <div class="confirm-block">
                            {!! $order->confirm == 'pending'
                                ? '<button class="btn btn-primary confirm updateField" data-field="confirm" data-value="confirm" data-title="' .
                                    __('form.order_confirmed') .
                                    '">' .
                                    __('button.confirm') .
                                    '</button>'
                                : __('form.confirmed') !!}
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
                        <div class="edit span edit-order" data-target="description">{{ __('form.edit') }}</div>
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
                        <div class="edit span edit-order" data-target="customerInfo">{{ __('form.edit') }}</div>
                    </div>
                </div>
                <div class="ibox-content order-customer-information">
                    <div class="customer-line">
                        <strong>N:</strong>
                        <span class="fullname">
                            {{ $order->fullname }}
                        </span>
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong>
                        <span class="phone">
                            {{ $order->phone }}
                        </span>
                    </div>
                    <div class="customer-line">
                        <strong>E:</strong>
                        <span class="email">
                            {{ $order->email }}
                        </span>
                    </div>
                    <div class="customer-line">
                        <strong>A:</strong>
                        <span class="address">
                            {{ $order->address }}
                        </span>
                    </div>
                    <div class="customer-line">
                        <strong>W:</strong> {{ $order->ward }}
                    </div>
                    <div class="customer-line">
                        <strong>D:</strong> {{ $order->district }}
                    </div>
                    <div class="customer-line">
                        <strong>P:</strong> {{ $order->province }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="orderId" value="{{ $order->id }}">
<input type="hidden" name="" class="ward_id" value="{{ $order->ward_id }}">
<input type="hidden" name="" class="district_id" value="{{ $order->district_id }}">
<input type="hidden" name="" class="province_id" value="{{ $order->province_id }}">
<script>
    var provinces =
        @json(
            $provinces->map(function ($item) {
                    return [
                        'id' => $item->code,
                        'name' => $item->name,
                    ];
                })->values());

    var attribute =
        "{{ base64_encode(json_encode(old('attribute', isset($product->attribute) ? json_decode($product->attribute, true) : []))) }}"; // lấy dữ liệu từ các ô input trong div hidden
    var variant =
        "{{ base64_encode(json_encode(old('variant', isset($product->variant) ? json_decode($product->variant, true) : []))) }}"

    var fullname = "{{ __('form.name') }}"
    var phone = "{{ __('form.phone') }}"
    var email = "{{ __('form.email') }}"
    var address = "{{ __('form.address') }}"
    var province = "{{ __('form.province') }}"
    var district = "{{ __('form.district') }}"
    var ward = "{{ __('form.ward') }}"
    var selectProvince = "{{ __('form.select_province') }}"
    var selectDistrict = "{{ __('form.select_district') }}"
    var selectWard = "{{ __('form.select_ward') }}"
    var cancelOrder = "{{ __('button.cancel_order') }}"
    var orderCanceled = "{{ __('form.order_canceled') }}"
    var confirmed = "{{ __('form.confirmed') }}"
    var canceled = "{{ __('form.canceled') }}"
</script>
