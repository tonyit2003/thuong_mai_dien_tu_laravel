<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">{{ __('form.product_receipt_name') }}</div>
        <div class="ibox-content">
            <table class="table table-striped table-bordered">
                <thead>
                    <th class="text-center" style="width: 20%">{{ __('table.user') }}</th>
                    <th class="text-center" style="width: 10%">{{ __('table.date_created') }}</th>
                    <th class="text-center" style="width: 20%">{{ __('table.supplier_information') }}</th>
                    <th class="text-center" style="width: 20%">{{ __('table.total_receipt') }}</th>
                    <th class="text-center" style="width: 20%">{{ __('table.actual_total') }}</th>
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
                            <td class="text-right text-danger">
                                <strong>
                                    {{ isset($productReceipt->actual_total) && $productReceipt->publish == 3 ? formatCurrency($productReceipt->actual_total) : __('table.undelivered') }}
                                </strong>
                            </td>
                            <td class="text-center" style="color: red">
                                @if ($productReceipt->publish == 0)
                                    {{ __('table.approved') }}
                                @elseif ($productReceipt->publish == 1)
                                    {{ __('table.unapproved') }}
                                @elseif ($productReceipt->publish == 2)
                                    {{ __('table.booking') }}
                                @elseif ($productReceipt->publish == 3)
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
            <table class="table table-striped table-bordered" id="productTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 25%">{{ __('table.product_name') }}</th>
                        <th class="text-center" style="width: 35%">{{ __('table.version') }}</th>
                        <th class="text-center" style="width: 10%">{{ __('table.quantity_imported') }}</th>
                        <th class="text-center" style="width: 10%">{{ __('table.actual_quantity_imported') }}</th>
                        <th class="text-center" style="width: 20%">{{ __('table.price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($formattedDetails as $formattedDetail)
                        <tr>
                            <td>{{ $formattedDetail['product_name'] }}</td>
                            <td>{{ $formattedDetail['variant_name'] }}</td>
                            <td class="text-center">{{ $formattedDetail['quantity'] }}</td>
                            <td class="text-center text-danger">
                                <strong>
                                    {{ isset($productReceipt->actual_total) && $productReceipt->publish == 3 ? $formattedDetail['actual_quantity'] : __('table.undelivered') }}
                                </strong>
                            </td>
                            <td class="text-right">{{ formatCurrency($formattedDetail['price']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
