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
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($customerCatalogues) && is_object($customerCatalogues))
            @php
                $canonical = \Illuminate\Support\Facades\App::getLocale();
                $canonical = $canonical == 'vn' ? 'vi' : $canonical;
            @endphp
            @foreach ($customerCatalogues as $customerCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $customerCatalogue->id }}"
                            class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ translateContent($customerCatalogue->name, $canonical) }}
                    </td>
                    <td class="text-center">
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
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('customer.catalogue.edit', $customerCatalogue->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.catalogue.delete', $customerCatalogue->id) }}">
                                        {{ __('table.delete') }}
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

{{ $customerCatalogues->links('pagination::bootstrap-4') }}
