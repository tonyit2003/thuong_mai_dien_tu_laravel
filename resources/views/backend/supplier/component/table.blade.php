<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.name') }}</th>
            <th class="text-center">{{ __('table.email') }}</th>
            <th class="text-center">{{ __('table.phone') }}</th>
            <th class="text-center">{{ __('table.address') }}</th>
            <th class="text-center">{{ __('table.fax') }}</th>
            <th class="text-center">{{ __('table.product_catalogue') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($suppliers) && is_object($suppliers))
            @php
                $previousSupplierId = null;
            @endphp
            @foreach ($suppliers as $supplier)
                @if ($previousSupplierId === $supplier->id)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            {{ $supplier->product_type_name ?? __('No Product Types') }}
                        </td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                @else
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" value="{{ $supplier->id }}" class="input-checkbox checkBoxItem" />
                        </td>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>
                            {{ $supplier->address }},
                            {{ optional($supplier->ward)->full_name }},
                            {{ optional($supplier->district)->full_name }},
                            {{ optional($supplier->province)->full_name }}
                        </td>
                        <td class="text-center">{{ $supplier->fax }}</td>
                        <td>
                            {{ $supplier->product_type_name ?? __('No Product Types') }}
                        </td>
                        <td class="text-center js-switch-{{ $supplier->id }}">
                            <input type="checkbox" value="{{ $supplier->publish }}" class="js-switch status" data-field="publish"
                                data-model="{{ $config['model'] }}" data-modelId="{{ $supplier->id }}"
                                {{ $supplier->publish == 1 ? 'checked' : '' }} />
                        </td>
                        <td class="text-center">
                            <div class="ibox-tools-button">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                    <strong style="min-width: 0px">...</strong>
                                </a>
                                <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                    <li>
                                        <a href="{{ route('supplier.edit', $supplier->id) }}">
                                            {{ __('table.update') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('supplier.delete', $supplier->id) }}">
                                            {{ __('table.delete') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endif
                @php
                    $previousSupplierId = $supplier->id;
                @endphp
            @endforeach
        @endif
    </tbody>
</table>

{{ $suppliers->links('pagination::bootstrap-4') }}
