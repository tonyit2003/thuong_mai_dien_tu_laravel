<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 3%">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center" style="width: 20%">{{ __('table.user') }}</th>
            <th class="text-center" style="width: 30%">{{ __('table.supplier_information') }}</th>
            <th class="text-center" style="width: 10%">{{ __('table.date_created') }}</th>
            <th class="text-center" style="width: 15%">{{ __('table.total') }}</th>
            <th class="text-center" style="width: 7%">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 15%">{{ __('table.actions') }}</th>
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
                    <td class="text-right">{{ formatCurrency($productReceipt->total) }}</td>
                    <td class="text-center" style="color: red">
                        @if ($productReceipt->publish == 0)
                            {{ __('table.approved') }}
                        @elseif ($productReceipt->publish == 1)
                            {{ __('table.unapproved') }}
                        @else
                            {{ __('table.instock') }}
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('receipt.browse', $productReceipt->id) }}" class="btn btn-warning" title="{{ __('table.browse') }}">
                            <i class="fa fa-check"></i>
                        </a>
                        <a href="{{ route('receipt.detail', $productReceipt->id) }}" class="btn btn-primary" title="{{ __('table.view') }}">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if ($productReceipt->publish == 1)
                            <a href="javascript:void(0);" class="btn btn-success disabled" title="{{ __('table.update') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        @else
                            <a href="{{ route('receipt.edit', $productReceipt->id) }}" class="btn btn-success" title="{{ __('table.update') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif
                        <a href="{{ route('receipt.delete', $productReceipt->id) }}" class="btn btn-danger" title="{{ __('table.delete') }}">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $productReceipts->links('pagination::bootstrap-4') }}
