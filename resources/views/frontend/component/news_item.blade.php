@php
    $image = $news->image;
    $name = $news->languages->first()->pivot->name;
    $canonical = write_url($news->languages->first()->pivot->canonical, true, true);
    $description = $news->languages->first()->pivot->description;
@endphp
<div class="product-item-2 product">
    <a href="{{ $canonical }}" class="image img-scaledown img-zoomin">
        <img src="{{ $image }}" alt="{{ $name }}">
    </a>
    <div class="info">
        <div class="info-wrapper">
            <h3 class="title">
                <a href="{{ $canonical }}" title="{{ $name }}">{{ $name }}</a>
            </h3>
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="description-news uk-flex uk-flex-bottom">
                    {!! $description !!}
                </div>
            </div>
        </div>
    </div>
</div>
