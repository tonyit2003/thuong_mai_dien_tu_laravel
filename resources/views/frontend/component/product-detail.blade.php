<div class="panel-body">
    @php
        $name = $product->name . ' - ' . $productVariant->languages->first()->pivot->name;
        $canonical =
            write_url($product->languages->first()->pivot->canonical, true, false) .
            '/uuid=' .
            $productVariant->uuid .
            config('apps.general.suffix');
        $price = getPrice($productVariant);
        $catName = $productCatalogue->name;
        $review = getReview($productVariant);
        $description = $product->description;
        $attributeCatalogues = $product->attributeCatalogue;
        $gallery =
            isset($productVariant->album) && $productVariant->album != ''
                ? explode(',', $productVariant->album)
                : json_decode($product->album);
    @endphp
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large-1-4">
            <div class="popup-gallery">
                <div class="swiper-container">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-wrapper big-pic">
                        @foreach ($gallery as $key => $val)
                            <div class="swiper-slide" data-swiper-autoplay="2000">
                                <a href="{{ $val }}" class="image img-cover">
                                    <img src="{{ $val }}" alt="{{ $val }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="swiper-container-thumbs">
                    <div class="swiper-wrapper pic-list">
                        @foreach ($gallery as $key => $val)
                            <div class="swiper-slide">
                                <span class="image img-cover">
                                    <img src="{{ $val }}" alt="{{ $val }}">
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-width-large-2-4">
            <div class="popup-product">
                <h1 class="title">
                    <span>
                        {{ $name }}
                    </span>
                </h1>
                <div class="rating">
                    <div class="uk-flex uk-flex-middle">
                        <div class="author">{{ __('info.evaluate') }}</div>
                        <div class="star">
                            <?php for($i = 0; $i<=4; $i++){ ?>
                            <i class="fa fa-star"></i>
                            <?php }  ?>
                        </div>
                        <div class="rate-number">(65 {{ __('unit.evaluate') }})</div>
                    </div>
                </div>
                {!! $price['html'] !!}
                <div class="description">
                    {!! $description !!}
                </div>
                @include('frontend.product.product.component.variant')
                <div class="quantity">
                    <div class="text">{{ __('info.quantity') }}</div>
                    <div class="uk-flex uk-flex-middle">
                        <div class="quantitybox uk-flex uk-flex-middle">
                            <div class="minus quantity-button"><img
                                    src="{{ asset('frontend/resources/img/minus.svg') }}" alt=""></div>
                            <input type="text" name="" value="1" class="quantity-text">
                            <div class="plus quantity-button"><img src="{{ asset('frontend/resources/img/plus.svg') }}"
                                    alt="">
                            </div>
                        </div>
                        <div class="btn-group uk-flex uk-flex-middle">
                            <div class="btn-item btn-1 addToCart" data-productid="{{ $product->id }}"
                                data-variantuuid="{{ $productVariant->uuid }}">
                                <a href="" title="">{{ __('info.addToCart') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-width-large-1-4">
            <div class="aside">
                @if (isset($category))
                    @foreach ($category as $key => $val)
                        @php
                            $name = $val['item']->languages->first()->pivot->name;
                        @endphp
                        <div class="aside-panel aside-category">
                            <div class="aside-heading">{{ $name }}</div>
                            @if (isset($val['children']) && count($val['children']))
                                <div class="aside-body">
                                    <ul class="uk-list uk-clearfix">
                                        @foreach ($val['children'] as $item)
                                            @php
                                                $itemName = $item['item']->languages->first()->pivot->name;
                                                $itemImage = $item['item']->image;
                                                $itemCanonical = write_url(
                                                    $item['item']->languages->first()->pivot->canonical,
                                                    true,
                                                    true,
                                                );
                                            @endphp
                                            <li class="mb20">
                                                <div class="categories-item-1">
                                                    <a href="{{ $itemCanonical }}" title="{{ $itemName }}"
                                                        class="uk-flex uk-flex-middle uk-flex uk-flex-space-between">
                                                        <div class="uk-flex uk-flex-middle">
                                                            <img src="{{ $itemImage }}" alt="{{ $itemName }}">
                                                            <span>{{ $itemName }}</span>
                                                        </div>
                                                    </a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
