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
                                <div> - {{ __('form.status_confirm') }}: </div>
                                <span class="badge">
                                    <div class="badge__tip"></div>
                                    <div class="badge-text">
                                        {{ __('statusOrder.confirm')[$order->confirm] }}
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
                                        @if ($order->delivery == 'pending')
                                            <div class="order-item-voucher">
                                                {{ __('form.quantity_in_stock') }}:
                                                @if ($val->quantityInStock > 0 && $val->quantityInStock >= $val->quantity)
                                                    <span class="text-navy">
                                                        {{ $val->quantityInStock }}
                                                        ({{ __('form.can_be_exported') }})
                                                    </span>
                                                @elseif ($val->quantityInStock == 0)
                                                    @php
                                                        $check = false;
                                                    @endphp
                                                    <span class="text-danger">
                                                        {{ $val->quantityInStock }} ({{ __('form.out_of_stock') }})
                                                    </span>
                                                @elseif($val->quantityInStock < $val->quantity)
                                                    @php
                                                        $check = false;
                                                    @endphp
                                                    <span class="text-danger">
                                                        {{ $val->quantityInStock }}
                                                        ({{ __('form.insufficient_quantity') }})
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
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
                                <td class="text-right">{{ formatCurrency($order->shipping) }}</td>
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
                        <div class="invoice-block">
                            @if (($order->delivery == 'processing' || $order->delivery == 'success') && $order->confirm == 'confirm')
                                <a class="btn btn-success" target="_blank"
                                    href="{{ write_url('invoice/' . $order->code . '.pdf', true, false) }}">{{ __('form.invoice') }}</a>
                                <a class="btn btn-primary confirm updateField" data-field="delivery"
                                    data-value="success"
                                    data-title="{{ __('form.delivery_successful') }}">{{ __('button.delivery_successful') }}</a>
                                <a href="#submitCancelOrder" rel="modal:open"
                                    class="btn btn-danger">{{ __('button.cancel_order') }}</a>
                            @endif
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
                                    {{ __('form.confirm_out_of_stock') }}
                                </div>
                            </div>
                        </div>
                        <div class="processing-block">
                            @if ($order->delivery == 'pending' && $order->confirm == 'confirm')
                                <a class="btn btn-primary confirm updateField" data-field="delivery"
                                    data-value="processing"
                                    data-title="{{ __('form.order_processing') }}">{{ __('button.out_of_stock') }}</a>
                            @else
                                <span class="text-success">{{ __('form.successful_export') }}</span>
                            @endif
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

<div id="submitCancelOrder" class="modal">
    <div class="panel-head text-center mb10">{{ __('info.notification') }}</div>
    <div class="panel-body payment-confirm">
        <p class="message-text">{!! __('form.confirm_order_deletion', ['orderCode' => $order->code]) !!}</p>
        <div class="text-right mt20">
            <a href="#" class="btn btn-danger updateField" data-field="confirm" data-value="cancel"
                data-title="{{ __('form.order_canceled') }}" data-returnStock="true">
                {{ __('button.cancel_order') }}
            </a>
            <a href="#" class="btn btn-success" rel="modal:close">{{ __('form.cancel') }}</a>
        </div>
    </div>
</div>

<input type="hidden" class="orderId" value="{{ $order->id }}">

<script>
    var invoiceButton = "{{ __('button.delivery_successful') }}"
    var successfulExport = "{{ __('form.successful_export') }}"
    var routeOutOfStock = "{{ route('order.outOfStock') }}"
    var invoiceTitle = "{{ __('form.invoice') }}"
    var invoiceUrl = "{{ write_url('invoice/' . $order->code . '.pdf', true, false) }}"
    var cancelOrder = "{{ __('button.cancel_order') }}"
    var canceled = "{{ __('form.canceled') }}"
</script>
