@if ($orders->isEmpty())
    <!-- Không có đơn hàng -->
    <div class="no-orders">
        <img src="frontend/img/noproduct.png" alt="No Orders" class="no-orders-image">
        <p class="no-orders-text">{{ __('info.no_order') }}</p>
    </div>
@else
    <!-- Hiển thị tất cả các đơn hàng -->
    @foreach ($orders as $order)
        <div class="order-container">
            <div class="order-status">
                <div></div>
                <div class="status-right">
                    <h3>
                        @if ($order->confirm == 'pending' && $order->delivery == 'pending')
                            {{ __('info.pending') }}
                        @elseif ($order->confirm == 'confirm' && $order->delivery == 'processing')
                            {{ __('info.processing') }}
                        @elseif ($order->confirm == 'confirm' && $order->delivery == 'success')
                            {{ __('info.success') }}
                        @else
                            {{ __('info.cancel') }}
                        @endif
                    </h3>
                </div>
            </div>
            <hr>
            <table class="order-table">
                @foreach ($order->details as $item)
                    <tr class="product-row">
                        <td class="product-image" style="width: 80px;">
                            <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        </td>
                        <td class="product-details">
                            <p class="product-name">{{ $item->name }}</p>
                            <p class="product-quantity">x{{ $item->quantity }}</p>
                        </td>
                        <td class="product-price" style="width: 200px;">
                            <p>
                                <span class="original-price">
                                    {{ $item->priceOriginal == $item->price ? '' : formatCurrency($item->priceOriginal) }}
                                </span>
                                <span style="font-size: 16px" class="discounted-price">
                                    {{ formatCurrency($item->price) }}
                                </span>
                            </p>
                        </td>
                    </tr>
                @endforeach
            </table>
            <hr>
            <div class="order-total">
                <p>{{ __('info.total') }}
                    <span>
                        {{ formatCurrency($order->details->sum(function ($item) {return $item->price * $item->quantity;})) }}
                    </span>
                </p>
            </div>
            <div class="order-actions">
                <a href="#" class="btn btn-default">{{ __('info.contact') }}</a>
            </div>
        </div>
    @endforeach
    <div class="text-center">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
@endif
