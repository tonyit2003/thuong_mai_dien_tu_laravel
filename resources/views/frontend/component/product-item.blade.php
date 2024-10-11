@php
    $name = $product->languages->first()->pivot->name . ' ' . $productVariant->languages->first()->pivot->name;
    $canonical =
        write_url($product->languages->first()->pivot->canonical, true, false) .
        '/id=' .
        $productVariant->id .
        config('apps.general.suffix');
    $image = image(explode(',', $productVariant->album)[0]);
    $price = getPrice($productVariant);
    $catName = '';
    foreach ($product->product_catalogues->toArray() as $key => $val) {
        if ($val['id'] == $product->product_catalogue_id) {
            $catName = $val['languages'][0]['pivot']['name'];
            break;
        }
    }
    $review = getReview($product);
@endphp
<div class="product-item product">
    @if ($price['percent'] !== 0)
        <div class="badge badge-bg2">-{{ $price['percent'] }}%</div>
    @endif
    <a href="{{ $canonical }}" class="image img-scaledown img-zoomin">
        <img src="{{ $image }}" alt="{{ $name }}">
    </a>
    <div class="info">
        <div class="category-title"><a href="{{ $canonical }}" title="{{ $name }}">{{ $catName }}</a></div>
        <h3 class="title">
            <a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a>
        </h3>
        <div class="rating">
            <div class="uk-flex uk-flex-middle">
                <div class="star">
                    @for ($j = 1; $j <= $review['star']; $j++)
                        <i class="fa fa-star"></i>
                    @endfor
                </div>
                <span class="rate-number">({{ $review['count'] }})</span>
            </div>
        </div>
        <div class="product-group">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                {!! $price['html'] !!}
                <div class="addcart">
                    {{-- {!! renderQuickBuy($product, $canonical, $name) !!} --}}
                </div>
            </div>
        </div>

    </div>
    <div class="tools">
        <a href="{{ $canonical }}" title="{{ $name }}"><img
                src="{{ asset('frontend/resources/img/trend.svg') }}" alt="{{ $name }}"></a>
        <a href="{{ $canonical }}" title="{{ $name }}"><img
                src="{{ asset('frontend/resources/img/wishlist.svg') }}" alt="{{ $name }}"></a>
        <a href="{{ $canonical }}" title="{{ $name }}"><img
                src="{{ asset('frontend/resources/img/compare.svg') }}" alt="{{ $name }}"></a>
        <a href="#popup" data-uk-modal title="{{ $name }}"><img
                src="{{ asset('frontend/resources/img/view.svg') }}" alt="{{ $name }}"></a>
    </div>
</div>
