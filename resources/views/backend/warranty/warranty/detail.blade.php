@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])

<style>
    .input-checkbox[readonly] {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>

@include('backend.dashboard.component.formError')

<form action="{{ route('warranty.warrantyConfirm') }}" method="post">
    @csrf
    <input type="hidden" value="{{ $order->id }}" name="order_id">
    <div class="order-wrapper">
        <div class="row">
            <div class="col-lg-10">
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
                                @php
                                    $warrantyVariants = $warranty_card->pluck('variant_uuid')->toArray();
                                    $warrantyStatus = $warranty_card->pluck('status')->toArray();
                                    $warrantyNotes = $warranty_card->pluck('notes')->toArray();
                                    $warrantyDateOfReceipt = $warranty_card
                                        ->pluck('date_of_receipt')
                                        ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                                        ->toArray();
                                @endphp

                                @foreach ($orderProducts as $key => $val)
                                    @php
                                        $status = $warrantyStatus[$key] ?? 'unknown';
                                    @endphp
                                    <tr class="order-item">
                                        <td style="width: 2%;">
                                            <input type="checkbox" name="product_id[]" value="{{ $val->product_id }}" class="input-checkbox"
                                                {{ $val->warranty_time < now() ? 'readonly' : '' }} {{ $status == 'active' ? 'readonly' : '' }}
                                                {{ in_array($val->variant_uuid, $warrantyVariants) && $status == 'active' ? 'checked' : '' }} />
                                            <input type="hidden" name="variant_uuid[]" value="{{ $val->variant_uuid }}" />
                                        </td>
                                        <td style="width: 10%;">
                                            <div class="image">
                                                <span class="image img-scaledown">
                                                    <img src="{{ isset($val->image) ? $val->image : 'backend/img/no-photo.png' }}"
                                                        alt="{{ $val->name }}">
                                                </span>
                                            </div>
                                        </td>
                                        <td style="width: 50%;">
                                            <div class="order-item-name">
                                                <div style="font-size: 14px">{{ $val->name }}</div>
                                                <strong style="color: red">{{ __('table.time_warranty') }}:
                                                    {{ $val->warranty_time > now() ? convertDatetime($val->warranty_time, 'd-m-Y') : 'Hết hạn bảo hành' }}
                                                </strong>
                                                <br>
                                                <span style="color: #000">{{ __('form.error') }} <span class="text-danger">(*)</span></span>
                                                <input style="color: #000" type="text"
                                                    value="{{ $status == 'active' ? $warrantyNotes[$key] : '' }}" class="form-control" name="notes[]"
                                                    placeholder="{{ __('form.enter_error') }}"
                                                    {{ $val->warranty_time < now() || $status == 'active' ? 'readonly' : '' }}>
                                            </div>
                                        </td>

                                        <td style="width: 7%; vertical-align: top;">
                                            <span>{{ __('form.date_of_receipt') }} </span><span class="text-danger">(*)</span>
                                            <br>
                                            <input type="date" name="date_of_receipt[]" class="form-control"
                                                {{ isset($warrantyDateOfReceipt[$key]) && $status == 'active' ? 'readonly' : '' }}
                                                value="{{ isset($warrantyDateOfReceipt[$key]) ? $warrantyDateOfReceipt[$key] : \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                                        </td>

                                        <td style="width: 10%;">
                                            <div class="order-item-price">
                                                {{ formatCurrency($val->price) }}
                                            </div>
                                        </td>

                                        <td style="width: 2%;">
                                            <div class="order-item-times">
                                                ✖
                                            </div>
                                        </td>
                                        <td style="width: 2%;">
                                            <div class="order-item-qty">
                                                {{ $val->quantity }}
                                            </div>
                                        </td>
                                        <td style="width: 10%;">
                                            <div class="order-item-subtotal">
                                                {{ formatCurrency($val->price * $val->quantity) }}
                                            </div>
                                        </td>
                                        <td style="width: 2% !important;">
                                            @if ($status == 'active')
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-repair-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @elseif ($status == 'expired')
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-process-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @elseif ($status == 'pending')
                                                <i class="fa fa-hourglass-start" style="color: orange; font-size: 24px;" title="Đang chờ xử lý"></i>
                                            @elseif ($status == 'completed')
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-complete-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @else
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-complete-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="7" class="text-right">{{ __('form.provisional_total') }}</td>
                                    <td class="text-right">{{ formatCurrency($order->totalPriceOriginal) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">{{ __('form.discount_v2') }}</td>
                                    <td class="text-right" style="color: red">
                                        - {{ formatCurrency($order->promotion['discount'] ?? 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right">{{ __('form.delivery') }}</td>
                                    <td class="text-right">{{ formatCurrency($val->shipping) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right"><strong>{{ __('form.final_total') }}</strong></td>
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
                                    <i class="fa fa-truck"></i>
                                </span>
                                <div class="payment-title">
                                    <div class="text_1">
                                        {{ __('form.confirm_warranty') }}
                                    </div>
                                </div>
                            </div>
                            <div class="confirm-block">
                                <button class="btn btn-primary" type="submit">{{ __('button.confirm') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 order-aside">
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
</form>
<input type="hidden" class="orderId" value="{{ $order->id }}">
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

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.input-checkbox[readonly]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('click', function(e) {
                e.preventDefault();
            });
        });
    });
</script>
