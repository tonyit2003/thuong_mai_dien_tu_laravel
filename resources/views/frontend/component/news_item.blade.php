@php
    $image = $news->image;
    $name = $news->languages->first()->pivot->name;
    $canonical = $news->languages->first()->pivot->canonical;
    $description = $news->languages->first()->pivot->description;
@endphp
<div class="product-item-2 product">
    <a href="{{ $canonical }}" class="image">
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
