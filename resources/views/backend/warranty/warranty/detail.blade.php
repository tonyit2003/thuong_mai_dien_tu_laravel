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
                                        // Lấy trạng thái từ danh sách bảo hành
                                        $warrantyData = $warrantyStatuses[$val->product_id] ?? null;
                                        $variantUuid = $warrantyData['variant_uuid'] ?? null;
                                        $status = $warrantyData['status'] ?? 'unknown';
                                        $notes = $warrantyData['notes'] ?? '';
                                        $dateOfReceipt = $warrantyData['date_of_receipt'] ?? null;
                                        $warrantyEndDate = Carbon\Carbon::parse($order->delivery_date)->addMonths($val->warranty_time);
                                    @endphp
                                    <tr class="order-item">
                                        <td style="width: 2%;">
                                            <input type="checkbox" id="checkbox-{{ $key }}"
                                                name="products[{{ $key }}][product_id]" value="{{ $val->product_id }}"
                                                class="input-checkbox" {{ $warrantyEndDate < now() ? 'readonly' : '' }}
                                                {{ $status === 'active' ? 'checked readonly' : '' }} />
                                            <input type="hidden" name="products[{{ $key }}][variant_uuid]"
                                                value="{{ $val->variant_uuid }}" />
                                            <input type="hidden" name="products[{{ $key }}][product_name]" value="{{ $val->name }}" />
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
                                                    {{ $warrantyEndDate > now() ? convertDatetime($warrantyEndDate, 'd-m-Y') : __('table.warranty_expired') }}
                                                </strong>
                                                <br>
                                                <span style="color: #000">{{ __('form.error') }} <span class="text-danger">(*)</span></span>
                                                <input type="text" name="products[{{ $key }}][notes]"
                                                    value="{{ $status === 'active' ? $notes : '' }}" class="form-control"
                                                    placeholder="{{ __('form.enter_error') }}"
                                                    {{ $status === 'active' || $warrantyEndDate < now() ? 'readonly' : '' }}>
                                            </div>
                                        </td>
                                        <td style="width: 7%; vertical-align: top;">
                                            <input type="date" name="products[{{ $key }}][date_of_receipt]"
                                                value="{{ $dateOfReceipt ? \Carbon\Carbon::parse($dateOfReceipt)->format('Y-m-d') : now()->format('Y-m-d') }}"
                                                class="form-control date-field" id="date-{{ $key }}"
                                                {{ $status === 'active' || $warrantyEndDate < now() ? 'readonly' : '' }}
                                                min="{{ now()->format('Y-m-d') }}">
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
                                            @if ($status === 'active')
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-repair-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @elseif ($status === 'expired')
                                                <div class="image">
                                                    <span class="image img-scaledown">
                                                        <img src="backend/img/icons8-process-100.png" alt="" style="width: 24px">
                                                    </span>
                                                </div>
                                            @elseif ($status === 'pending')
                                                <i class="fa fa-hourglass-start" style="color: orange; font-size: 24px;" title="Đang chờ xử lý"></i>
                                            @elseif ($status === 'completed')
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
        // Lấy tất cả checkbox
        const checkboxes = document.querySelectorAll('.input-checkbox');

        checkboxes.forEach(function(checkbox) {
            const key = checkbox.id.split('-')[1]; // Lấy key từ id của checkbox
            const notesField = document.getElementById(`notes-${key}`);
            const dateField = document.getElementById(`date-${key}`);

            // Khóa trường notes và date nếu checkbox chưa được check
            const toggleFields = () => {
                if (checkbox.checked) {
                    notesField.removeAttribute('readonly');
                    dateField.removeAttribute('readonly');
                } else {
                    notesField.setAttribute('readonly', 'true');
                    dateField.setAttribute('readonly', 'true');
                }
            };

            // Gọi toggle khi trang tải
            toggleFields();

            // Thay đổi trạng thái khi checkbox được click
            checkbox.addEventListener('change', toggleFields);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('warranty.warrantyConfirm') }}"]');
        const checkboxes = document.querySelectorAll('.input-checkbox');
        const submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(e) {
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            if (!anyChecked) {
                e.preventDefault();
                alert('Vui lòng chọn sản phẩm cần bảo hành.');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action="{{ route('warranty.warrantyConfirm') }}"]');
        const checkboxes = document.querySelectorAll('.input-checkbox');

        form.addEventListener('submit', function(e) {
            let hasError = false;

            checkboxes.forEach(function(checkbox) {
                const key = checkbox.id.split('-')[1]; // Get the key from the checkbox ID
                const notesField = form.querySelector(`input[name="products[${key}][notes]"]`);

                if (checkbox.checked) {
                    // If the checkbox is checked but the notes field is empty, show an alert and prevent submission
                    if (!notesField || notesField.value.trim() === '') {
                        hasError = true;
                        notesField.classList.add('is-invalid'); // Highlight the field with a class
                        alert(`Vui lòng nhập ghi chú cho sản phẩm ở dòng ${parseInt(key) + 1}.`);
                    } else {
                        notesField.classList.remove('is-invalid'); // Remove error class if valid
                    }
                }
            });

            if (hasError) {
                e.preventDefault(); // Prevent form submission if there's an error
            }
        });
    });
</script>
