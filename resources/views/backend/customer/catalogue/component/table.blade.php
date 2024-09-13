<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.customer_group_name') }}</th>
            <th class="text-center">{{ __('table.member_count') }}</th>
            <th class="text-center">{{ __('table.description') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($customerCatalogues) && is_object($customerCatalogues))
            @foreach ($customerCatalogues as $customerCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $customerCatalogue->id }}"
                            class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $customerCatalogue->name }}
                    </td>
                    <td class="text-center">
                        {{-- xem thuộc tính bằng dd() --}}
                        {{ $customerCatalogue->customers_count }} {{ __('unit.members') }}
                    </td>
                    <td>
                        {{ $customerCatalogue->description }}
                    </td>
                    <td class="text-center js-switch-{{ $customerCatalogue->id }}">
                        <input type="checkbox" value="{{ $customerCatalogue->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $customerCatalogue->id }}"
                            {{ $customerCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('customer.catalogue.edit', $customerCatalogue->id) }}"
                            class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('customer.catalogue.delete', $customerCatalogue->id) }}"
                            class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $customerCatalogues->links('pagination::bootstrap-4') }}
