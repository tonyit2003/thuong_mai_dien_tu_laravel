@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])

<style>
    .input-checkbox[readonly] {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
    }
</style>

@include('backend.dashboard.component.formError')

@php
    $warrantyVariants = $warranty_card->pluck('variant_uuid')->toArray();
    $warrantyNotes = $warranty_card->pluck('notes')->toArray();

    $statuses = [
        'active' => __('table.active'),
        'completed' => __('table.completed'),
    ];

    $activeProducts = $orderProducts->filter(function ($product) use ($warranty_card) {
        $currentStatus = $warranty_card->where('variant_uuid', $product->variant_uuid)->first()->status ?? null;
        return in_array($currentStatus, ['active', 'pending']);
    });
@endphp

<form action="{{ route('warranty.warrantyConfirmRepair') }}" method="post">
    @csrf
    <input type="hidden" value="{{ $order->id }}" name="order_id">
    @foreach ($activeProducts as $product)
        @php
            // Tìm warranty card dựa trên variant_uuid
            $currentCard = $warranty_card->firstWhere('variant_uuid', $product->variant_uuid);
            $currentId = $currentCard->id ?? null; // Lấy id từ card
        @endphp
        <input type="hidden" name="id[]" value="{{ $currentId }}">
    @endforeach

    <div class="order-wrapper">
        <div class="row">
            <div class="col-lg-12">
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
                            <div class="">
                                <div class="ibox-title-right">
                                    <span>{{ __('form.source_v2') }}: {{ __('form.website') }}</span>
                                </div>
                                <div class="ibox-title-right form-date" style="margin-top: 10px;">
                                    <input type="text" name="warranty_end_date"
                                        value="{{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}"
                                        class="form-control datepickerApproved" placeholder="" autocomplete="off" readonly>
                                    <span><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table-order">
                            <tbody>
                                @foreach ($activeProducts as $key => $val)
                                    @php
                                        $currentStatus = $warranty_card->where('variant_uuid', $val->variant_uuid)->first()->status ?? null;
                                    @endphp
                                    <tr class="order-item">
                                        <td style="width: 2%;">
                                            <input type="checkbox" name="product_id[]" value="{{ $val->product_id }}" class="input-checkbox"
                                                {{ $val->created_at->addMonths($val->warranty_time) < now() ? 'readonly' : '' }} />
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
                                        <td style="width: 78%;">
                                            <div class="order-item-name">
                                                <div style="font-size: 14px">{{ $val->name }}</div>
                                                <strong style="color: red">{{ __('table.time_warranty') }}:
                                                    {{ $val->created_at->addMonths($val->warranty_time) > now() ? convertDatetime($val->created_at->addMonths($val->warranty_time), 'H:i d-m-Y') : __('table.warranty_expired') }}
                                                </strong>
                                                <br>
                                                <span style="color: #000">{{ __('form.note') }} <span class="text-danger">(*)</span></span>
                                                <input style="color: #000" type="text" value="{{ $warrantyNotes[$key] ?? '' }}"
                                                    class="form-control" name="notes[]" placeholder="{{ __('form.enter_note') }}" readonly>
                                                <br>
                                                <select name="status[]" class="form-control setupSelect2" style="width: 160px">
                                                    @foreach ($statuses as $statusKey => $statusLabel)
                                                        <option value="{{ $statusKey }}"
                                                            {{ $currentStatus == $statusKey ? 'selected' : ($currentStatus === null && $statusKey == 'completed' ? 'selected' : '') }}>
                                                            {{ $statusLabel }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
                                        {{ __('form.confirm_repair') }}
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
