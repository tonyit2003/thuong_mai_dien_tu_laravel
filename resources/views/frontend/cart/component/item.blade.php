<div class="panel-body">
    @if (isset($carts) && count($carts))
        <div class="cart-list">
            @foreach ($carts as $key => $val)
                <div class="cart-item">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-small-1-1 uk-width-medium-1-5">
                            <div class="cart-item-image">
                                <span class="image img-scaledown">
                                    <img src="{{ isset($val->image) ? $val->image : 'backend/img/no-photo.png' }}"
                                        alt="">
                                </span>
                                <span class="cart-item-number">{{ $val->quantity }}</span>
                            </div>
                        </div>
                        <div class="uk-width-small-1-1 uk-width-medium-4-5">
                            <div class="cart-item-info">
                                <h3 class="title">
                                    <span>{{ $val->name }}</span>
                                </h3>
                                <div class="cart-item-action uk-flex uk-flex-middle uk-flex-space-between">
                                    <div class="cart-item-qty">
                                        <button type="button" class="btn-qty minus">-</button>
                                        <input type="text" class="input-qty int" value="{{ $val->quantity }}">
                                        <div class="uk-hidden cart-info">
                                            <input type="text" class="customer_id" value="{{ $val->customer_id }}">
                                            <input type="text" class="product_id" value="{{ $val->product_id }}">
                                            <input type="text" class="variant_uuid" value="{{ $val->variant_uuid }}">
                                        </div>
                                        <button type="button" class="btn-qty plus">+</button>
                                    </div>
                                    <div class="price cart-item-price" style="margin-bottom: 0px">
                                        <div class="cart-price-sale price-sale">
                                            {{ formatCurrency($val->price) }}</div>
                                    </div>
                                    <div class="cart-item-remove" data-customer-id="{{ $val->customer_id }}"
                                        data-product-id="{{ $val->product_id }}"
                                        data-variant-uuid="{{ $val->variant_uuid }}">
                                        <span>✖</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
