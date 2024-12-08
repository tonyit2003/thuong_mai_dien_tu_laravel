<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">{{ __('form.product_receipt_name') }}</div>
        <div class="ibox-content">
            <table class="table table-striped table-bordered">
                <thead>
                    <th class="text-center" style="width: 20%">{{ __('table.user') }}</th>
                    <th class="text-center" style="width: 10%">{{ __('table.date_created') }}</th>
                    <th class="text-center" style="width: 30%">{{ __('table.supplier_information') }}</th>
                    <th class="text-center" style="width: 25%">{{ __('table.total_receipt') }}</th>
                    <th class="text-center" style="width: 15%">{{ __('table.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($productReceipt) && is_object($productReceipt))
                        <tr>
                            <td>{{ $productReceipt->user->name }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($productReceipt->date_created)->format('d/m/Y') }}</td>
                            <td>{{ $productReceipt->suppliers ? $productReceipt->suppliers->name : 'N/A' }}</td>
                            <td class="text-right">{{ formatCurrency($productReceipt->total) }}</td>
                            <td class="text-center" style="color: red">
                                @if ($productReceipt->publish == 0)
                                    {{ __('table.approved') }}
                                @elseif ($productReceipt->publish == 1)
                                    {{ __('table.unapproved') }}
                                @elseif($productReceipt->publish == 2)
                                    {{ __('table.booking') }}
                                @else
                                    {{ __('table.delivered') }}
                                @endif
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">{{ __('table.receipt_detail') }}</div>
        <div class="ibox-content">

            <div class="filter-wrapper">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <div class="uk-flex uk-flex-middle" style="margin-left: auto;" id="keywordInput">
                        <label for="" class="control-label text-left mr5">{{ __('form.date_approved') }} <span
                                class="text-danger">(*)</span></label>
                        <div class="form-date">
                            <input type="text" name="date_approved" readonly
                                value="{{ old('date_approved', $productReceipt->date_approved ?? now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i')) }}"
                                class="form-control datepickerApproved" placeholder="" autocomplete="off">
                            <span><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered" id="productTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 25%">{{ __('table.product_name') }}</th>
                        <th class="text-center" style="width: 35%">{{ __('table.version') }}</th>
                        <th class="text-center" style="width: 10%">{{ __('table.actual_quantity_imported') }}</th>
                        <th class="text-center" style="width: 15%">{{ __('table.quantity_imported') }}</th>
                        <th class="text-center" style="width: 15%">{{ __('table.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formattedDetails as $index => $formattedDetail)
                        <tr>
                            <td>{{ $formattedDetail['product_name'] }}</td>
                            <td>{{ $formattedDetail['variant_name'] }}</td>
                            <td class="text-center">
                                <input type="hidden" name="quantity[]" value="{{ $formattedDetail['quantity'] }}">
                                {{ $formattedDetail['quantity'] }}
                            </td>
                            <td class="text-center">
                                <input type="text" name="actualQuantity[]" class="form-control mr10 int" placeholder=""
                                    value="{{ $formattedDetail['quantity'] }}">
                            </td>
                            <td class="text-right">
                                {{ formatCurrency($formattedDetail['price']) }}
                                <input type="hidden" name="price[]" class="form-control mr10 int" placeholder=""
                                    value="{{ $formattedDetail['price'] }}">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                <h3>{{ __('table.actual_total') }}: <strong class="text-danger total">0 {{ __('table.unit') }}</strong></h3>
            </div>
        </div>
    </div>
</div>

<script>
    var no_product = "{{ __('table.no_product') }}";
    var price = "{{ __('form.enter_price') }}";
    var productReceiptId = "{{ $productReceipt->id ?? 0 }}";
    var unit = "{{ __('table.unit') }}";
</script>
