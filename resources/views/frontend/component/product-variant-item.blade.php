@php
    $name = $productVariant->product_name . ' - ' . $productVariant->name;
    $canonical =
        write_url($productVariant->product_canonical, true, false) .
        '/uuid=' .
        $productVariant->uuid .
        config('apps.general.suffix');
    $image = isset($productVariant->album)
        ? image(explode(',', $productVariant->album)[0])
        : 'backend/img/no-photo.png';
    $price = getPrice($productVariant);
    $catName = $productVariant->product_catalogue->languages->first()->pivot->name;
    $totalReview = isset($productVariant->reviews) ? $productVariant->reviews->count() : 0;
    $totalRate = isset($productVariant->reviews) ? number_format($productVariant->reviews->avg('score'), 1) : 0;
    $starPercent = isset($productVariant->reviews) ? ($totalRate / 5) * 100 : 0;
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
        @if ($totalReview !== 0)
            <div class="rating">
                <div class="uk-flex uk-flex-middle">
                    <div class="star-rating" style="--star-width: {{ $starPercent }}%">
                        <div class="stars"></div>
                    </div>
                    <span class="rate-number">({{ $totalReview }})</span>
                </div>
            </div>
        @endif
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
