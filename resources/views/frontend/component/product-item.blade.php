@php
    $name = $product->languages->first()->pivot->name;
    $canonical = write_url($product->languages->first()->pivot->canonical, true, true);
    $image = image($product->image);
    $price = getPrice($product);
    $catNameArray = array_map(
        function ($category, $product) {
            return $category['id'] === $product->product_catalogue_id ? $category['languages'][0]['pivot']['name'] : '';
        },
        $product->product_catalogues->toArray(),
        [$product],
    );
    $catName = isset($catNameArray[0]) ? $catNameArray[0] : '';
    $review = getReview($product);
@endphp
<div class="product-item product">
    @if ($price['percent'] !== 0)
        <div class="badge badge-bg2">-{{ $price['percent'] }}%</div>
    @endif
    <a href="{{ $canonical }}" class="image">
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
                    {!! renderQuickBuy($product, $canonical, $name) !!}
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
