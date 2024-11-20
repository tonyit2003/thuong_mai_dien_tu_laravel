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
        $content = $product->content ?? '';
    @endphp
    <div class="uk-grid uk-grid-medium">
        <div class="uk-width-large-3-4">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-3">
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
                <div class="uk-width-large-2-3">
                    <div class="popup-product">
                        <h1 class="title">
                            <span>
                                {{ $name }}
                            </span>
                        </h1>
                        <div class="rating">
                            {{-- <div class="uk-flex uk-flex-middle">
                                <div class="author">{{ __('info.evaluate') }}</div>
                                <div class="star">
                                    <?php for($i = 0; $i<=4; $i++){ ?>
                                    <i class="fa fa-star"></i>
                                    <?php }  ?>
                                </div>
                                <div class="rate-number">(65 {{ __('unit.evaluate') }})</div>
                            </div> --}}
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
                                    <div class="plus quantity-button"><img
                                            src="{{ asset('frontend/resources/img/plus.svg') }}" alt="">
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
            </div>
            <div id="cpsContent" class="cps-block-content">
                <div style="padding: 20px">
                    {!! $content !!}
                </div>
                <div class="cps-block-content_btn-showmore">
                    <a class="btn-show-more button__content-show-more">
                        {{ __('info.show_more') }}
                        <div style="margin-left: 10px">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="10" height="10">
                                <path
                                    d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z">
                                </path>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
            @include('frontend.product.product.component.review', [
                'model' => $product,
                'modelVariant' => $productVariant,
            ])
        </div>
        <div class="uk-width-large-1-4">
            @if (isset($product->generalAttribute) && count($product->generalAttribute))
                <div class="specifications">
                    <div class="cps-block-technicalInfo">
                        <div class="is-flex is-justify-content-space-between is-align-items-center">
                            <h2 class="title is-6">{{ __('info.specifications') }}</h2>
                        </div>
                        <ul class="technical-content">
                            @foreach ($product->generalAttribute as $key => $val)
                                <li
                                    class="technical-content-item is-flex is-align-items-center is-justify-content-space-between">
                                    <p>{{ $key }}</p>
                                    <div>{{ $val }}</div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- <div class="aside">
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
            </div> --}}
        </div>
    </div>
</div>
