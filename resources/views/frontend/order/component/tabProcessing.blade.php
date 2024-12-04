@php
    // Lọc danh sách đơn hàng đang vận chuyển
    $shippingOrders = $orders->filter(function ($order) {
        return $order->confirm == 'confirm' && $order->delivery == 'processing';
    });
@endphp

@if ($shippingOrders->isEmpty())
    <!-- Không có đơn hàng -->
    <div class="no-orders">
        <img src="frontend/img/noproduct.png" alt="No Orders" class="no-orders-image">
        <p class="no-orders-text">Chưa có đơn hàng</p>
    </div>
@else
    <!-- Hiển thị danh sách đơn hàng đang vận chuyển -->
    @foreach ($shippingOrders as $order)
        <div class="order-container">
            <div class="order-status">
                <div></div>
                <div class="status-right">
                    <h3>Đang vận chuyển</h3>
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
                <p>Thành tiền: <span>{{ formatCurrency($order->details->sum('price')) }}</span></p>
            </div>
            <div class="order-actions">
                <a href="#" class="btn btn-default">Liên hệ người bán</a>
            </div>
        </div>
    @endforeach
@endif
