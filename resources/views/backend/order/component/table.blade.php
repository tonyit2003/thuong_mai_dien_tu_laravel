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
            {{-- <th class="text-center">
                {{ __('table.customer') }}
            </th> --}}
            <th class="text-center">
                {{ __('table.provisional_total') }}
            </th>
            <th class="text-center">
                {{ __('table.discount_v2') }}
            </th>
            <th class="text-center">
                {{ __('table.totalFinal') }}
            </th>
            <th style="width: 120px" class="text-center">
                {{ __('table.status') }}
            </th>
            <th class="text-center">
                {{ __('table.delivery') }}
            </th>
            <th class="text-center">
                {{ __('table.pay') }}
            </th>
            <th class="text-center">
                {{ __('table.method') }}
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
                        @php
                            $link =
                                isset($config['type']) && $config['type'] == 'index'
                                    ? route('order.detail', $order->id)
                                    : route('order.detailOutOfStock', $order->id);
                        @endphp
                        <a href="{{ $link }}">{{ $order->code }}</a>
                    </td>
                    <td>
                        {{ convertDatetime($order->created_at, 'H:i d-m-Y') }}
                    </td>
                    {{-- <td>
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
                    </td> --}}
                    <td class="text-right">
                        {{ formatCurrency($order->totalPriceOriginal) }}
                    </td>
                    <td class="text-right" style="color: red">
                        {{ formatCurrency($order->promotion['discount'] ?? 0) }}
                    </td>
                    <td class="text-right">
                        {{ formatCurrency($order->totalPrice) }}
                    </td>
                    <td class="text-center">
                        @if ($order->confirm == 'cancel')
                            <span class="cancel-badge">{{ __('statusOrder.confirm')[$order->confirm] }}</span>
                        @elseif($order->confirm == 'confirm')
                            <span class="confirm-badge">{{ __('statusOrder.confirm')[$order->confirm] }}</span>
                        @else
                            <span class="pending-badge">{{ __('statusOrder.confirm')[$order->confirm] }}</span>
                        @endif
                    </td>
                    {{-- @foreach (__('statusOrder') as $keyItem => $item)
                        @if ($keyItem == 'confirm')
                            @continue
                        @endif
                        <td class="text-center">
                            @if ($order->confirm != 'cancel')
                                <select name="{{ $keyItem }}" class="setupSelect2 updateBadge"
                                    data-field="{{ $keyItem }}">
                                    @foreach ($item as $keyOption => $option)
                                        @if ($keyOption == 'none')
                                            @continue
                                        @endif
                                        <option {{ $keyOption == $order->{$keyItem} ? 'selected' : '' }}
                                            value="{{ $keyOption }}">
                                            {{ $option }}</option>
                                    @endforeach
                                </select>
                            @else
                                -
                            @endif
                            <input type="hidden" class="confirm" value="{{ $order->confirm }}">
                            <input type="hidden" class="changeOrderStatus" value="{{ $order->{$keyItem} }}">
                        </td>
                    @endforeach --}}
                    <td class="text-center">
                        {{ __('statusOrder.delivery')[$order->delivery] }}
                    </td>
                    <td class="text-center">
                        {{ __('statusOrder.payment')[$order->payment] }}
                    </td>
                    <td class="text-center">
                        <img style="max-width: 54px;"
                            title="{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}"
                            src="{{ array_column(__('payment.method'), 'image', 'name')[$order->method] ?? '-' }}"
                            alt="{{ array_column(__('payment.method'), 'title', 'name')[$order->method] ?? '-' }}">
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $orders->links('pagination::bootstrap-4') }}
<script>
    var mustConfirmOrder = "{{ __('toast.must_confirm_order') }}"
</script>
