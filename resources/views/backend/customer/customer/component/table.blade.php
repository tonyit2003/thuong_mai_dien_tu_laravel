<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.name') }}
            </th>
            <th class="text-center">
                {{ __('table.email') }}
            </th>
            <th class="text-center">
                {{ __('table.phone') }}
            </th>
            <th class="text-center">
                {{ __('table.address') }}
            </th>
            <th class="text-center">
                {{ __('table.customer_group') }}
            </th>
            <th class="text-center">
                {{ __('table.source') }}
            </th>
            <th class="text-center" style="width: 100px">
                {{ __('table.status') }}
            </th>
            <th class="text-center" style="width: 50px">
                {{ __('table.actions') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($customers) && is_object($customers))
            @foreach ($customers as $customer)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $customer->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $customer->name }}
                    </td>
                    <td>
                        {{ $customer->email }}
                    </td>
                    <td>
                        {{ $customer->phone }}
                    </td>
                    <td>
                        {{ $customer->address }},
                        {{ optional($customer->ward)->full_name }},
                        {{ optional($customer->district)->full_name }},
                        {{ optional($customer->province)->full_name }}
                    </td>
                    <td class="text-center">
                        {{ $customer->customer_catalogues->name }}
                    </td>
                    <td class="text-center">
                        {{ $customer->sources->name }}
                    </td>
                    <td class="text-center js-switch-{{ $customer->id }}">
                        <input type="checkbox" value="{{ $customer->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $customer->id }}"
                            {{ $customer->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('customer.edit', $customer->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('customer.delete', $customer->id) }}">
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

{{ $customers->links('pagination::bootstrap-4') }}
