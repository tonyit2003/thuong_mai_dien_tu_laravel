<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 3%">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center" style="width: 20%">{{ __('table.user') }}</th>
            <th class="text-center" style="width: 20%">{{ __('table.supplier_information') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.date_created') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.date_approved') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.date_booking') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.date_delivered') }}</th>
            <th class="text-center" style="width: 10%">{{ __('table.total_receipt') }}</th>
            <th class="text-center" style="width: 10%">{{ __('table.actual_total') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 5%">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($productReceipts) && is_object($productReceipts))
            @foreach ($productReceipts as $productReceipt)
                <tr id="{{ $productReceipt->id }}">
                    <td class="text-center">
                        <input type="checkbox" value="{{ $productReceipt->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>{{ $productReceipt->user->name }}</td>
                    <td>{{ $productReceipt->suppliers ? $productReceipt->suppliers->name : 'N/A' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($productReceipt->date_created)->format('d/m/Y') }}</td>
                    <td class="text-center {{ isset($productReceipt->date_of_receipt) ? 'text-dark' : 'text-danger' }}">
                        {{ isset($productReceipt->date_of_receipt) ? \Carbon\Carbon::parse($productReceipt->date_of_receipt)->format('d/m/Y') : __('table.approved') }}
                    </td>
                    <td class="text-center {{ isset($productReceipt->date_of_booking) ? 'text-dark' : 'text-danger' }}">
                        {{ isset($productReceipt->date_of_booking) ? \Carbon\Carbon::parse($productReceipt->date_of_booking)->format('d/m/Y') : __('table.unbooking') }}
                    </td>
                    <td class="text-center {{ isset($productReceipt->date_approved) ? 'text-dark' : 'text-danger' }}">
                        {{ isset($productReceipt->date_approved) ? \Carbon\Carbon::parse($productReceipt->date_approved)->format('d/m/Y') : __('table.undelivered') }}
                    </td>
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
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -185px">
                                <li>
                                    @if ($productReceipt->publish != 3 && $productReceipt->publish == 1)
                                        <a href="{{ route('receipt.instock', $productReceipt->id) }}">
                                            {{ __('table.check_approved') }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0);" class="disabled">
                                            {{ __('table.check_approved') }}
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    @if ($productReceipt->publish != 1 && $productReceipt->publish != 3)
                                        <a href="{{ route('receipt.edit', $productReceipt->id) }}">
                                            {{ __('table.update') }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0);" class="disabled">
                                            {{ __('table.update') }}
                                        </a>
                                    @endif
                                </li>
                                <li>
                                    <a href="{{ route('receipt.delete', $productReceipt->id) }}">
                                        {{ __('table.delete') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('receipt.detail', $productReceipt->id) }}">
                                        {{ __('table.view') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $productReceipts->links('pagination::bootstrap-4') }}
