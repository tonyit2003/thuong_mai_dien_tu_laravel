<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.order_code') }}
            </th>
            <th class="text-center">
                {{ __('table.creation_date') }}
            </th>
            <th class="text-center">
                {{ __('table.customer') }}
            </th>
            <th class="text-center">
                {{ __('table.pay') }}
            </th>
            <th class="text-center" style="width: 50px">
                {{ __('table.actions') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($orders) && is_object($orders))
            @foreach ($orders as $order)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $order->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('warranty.repairDetail', $order->id) }}">{{ $order->code }}</a>
                    </td>
                    <td class="text-center">
                        {{ convertDatetime($order->created_at, 'H:i d-m-Y') }}
                    </td>
                    <td>
                        <div>
                            <b>N:</b> {{ $order->fullname }}
                        </div>
                        <div>
                            <b>P:</b> {{ $order->phone }}
                        </div>
                        <div>
                            <b>E:</b> {{ $order->email }}
                        </div>
                        <div>
                            <b>A:</b>
                            {{ getAddress($order->province_id, $order->district_id, $order->ward_id, $order->address) }}
                        </div>
                    </td>
                    <td class="text-center">
                        {{ __('statusOrder.payment')[$order->payment] }}
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            {{-- <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('user.edit', $user->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.delete', $user->id) }}">
                                        {{ __('table.delete') }}
                                    </a>
                                </li>
                            </ul> --}}
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

@if ($orders->isNotEmpty())
    {{ $orders->links('pagination::bootstrap-4') }}
@else
    <p>{{ __('form.null') }}</p>
@endif

<script>
    var mustConfirmOrder = "{{ __('toast.must_confirm_order') }}"
</script>
