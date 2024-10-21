<div class="panel-foot">
    <h2 class="cart-heading">
        <span>{{ __('form.payment_method') }}</span>
    </h2>
    <div class="cart-method mb30">
        @foreach (__('payment.method') as $key => $val)
            <label for="{{ $val['name'] }}" class="uk-flex uk-flex-middle method-item">
                <input type="radio" name="method" value="{{ $val['name'] }}" {{ $key == 0 ? 'checked' : '' }}
                    id="{{ $val['name'] }}">
                <span class="image">
                    <img src="{{ asset($val['image']) }}" alt="">
                </span>
                <span class="title">{{ $val['title'] }}</span>
            </label>
        @endforeach
    </div>
</div>
