@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        @include('frontend.component.slide')
        <div class="panel-category page-setup">
            <div class="uk-container uk-container-center">
                @if (isset($widgets[App\Enums\WidgetEnum::CATEGORY_MENU]->object))
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle">
                            <h2 class="heading-1"><span>{{ __('homePage.product_category') }}</span></h2>
                            @include('frontend.component.catalogue', [
                                'category' => $widgets[App\Enums\WidgetEnum::CATEGORY_MENU],
                            ])
                        </div>
                    </div>
                @endif
                @if (isset($widgets[App\Enums\WidgetEnum::CATEGORY]->object))
                    <div class="panel-body">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($widgets[App\Enums\WidgetEnum::CATEGORY]->object as $key => $val)
                                    @php
                                        $name = $val->languages->first()->pivot->name;
                                        $canonical = write_url(
                                            $val->languages->first()->pivot->canonical ?? '',
                                            true,
                                            true,
                                        );
                                        $image = $val->image;
                                        $productCount = $val->products_count;
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="category-item bg-<?php echo rand(1, 7); ?>">
                                            <a href="{{ $canonical }}" class="image img-scaledown img-zoomin">
                                                <img src="{{ $image }}" alt="{{ $name }}">
                                            </a>
                                            <div class="title">
                                                <a href="{{ $canonical }}"
                                                    title="{{ $name }}">{{ $name }}</a>
                                            </div>
                                            {{-- <div class="total-product">{{ $productCount }} {{ __('unit.product') }}</div> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @if (isset($slides[App\Enums\SlideEnum::BANNER]['item']))
            <div class="panel-banner">
                <div class="uk-container uk-container-center">
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            @foreach ($slides[App\Enums\SlideEnum::BANNER]['item'] as $key => $val)
                                @php
                                    $name = $val['name'];
                                    $description = $val['description'];
                                    $image = $val['image'];
                                    $canonical = write_url($val['canonical'] ?? '', true, true);
                                @endphp
                                <div class="uk-width-large-1-3">
                                    <div class="banner-item">
                                        <span class="image">
                                            <img src="{{ $image }}" alt="{{ $name }}">
                                        </span>
                                        <div class="banner-overlay">
                                            <div class="banner-title">{!! $description !!}</div>
                                            <a class="btn-shop" href="{{ $canonical }}"
                                                title="{{ $name }}">{{ __('homePage.buy_now') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (isset($widgets[App\Enums\WidgetEnum::CATEGORY_HOME]->object))
            @foreach ($widgets[App\Enums\WidgetEnum::CATEGORY_HOME]->object as $category)
                @php
                    $categoryName = $category->languages->first()->pivot->name;
                    $categoryCanonical = write_url($category->languages->first()->pivot->canonical, true, true);
                    $children = $category->children ?? null;
                @endphp
                <div class="panel-popular">
                    <div class="uk-container uk-container-center">
                        <div class="panel-head">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <h2 class="heading-1">
                                    <a href="{{ $categoryCanonical }}"
                                        title="{{ $categoryName }}">{{ $categoryName }}</a>
                                </h2>
                                @if (isset($children))
                                    <div class="category-children">
                                        <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                            <li class="">
                                                <a href="{{ $categoryCanonical }}"
                                                    title="{{ $categoryName }}">{{ __('homePage.all') }}</a>
                                            </li>
                                            @foreach ($children as $child)
                                                @php
                                                    $childName = $child->languages->first()->pivot->name;
                                                    $childCanonical = write_url(
                                                        $child->languages->first()->pivot->canonical,
                                                        true,
                                                        true,
                                                    );
                                                @endphp
                                                <li class="">
                                                    <a href="{{ $childCanonical }}"
                                                        title="{{ $childName }}">{{ $childName }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if (isset($category->products) && count($category->products))
                            <div class="panel-body">
                                <div class="uk-grid uk-grid-medium">
                                    @foreach ($category->products as $product)
                                        @foreach ($product->product_variants as $productVariant)
                                            <div class="uk-width-large-1-5 mb20">
                                                @include('frontend.component.product-item', [
                                                    'product' => $product,
                                                    'product_variant' => $productVariant,
                                                ])
                                            </div>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
        <div class="panel-bestseller">
            <div class="uk-container uk-container-center">
                <div class="panel-head">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h2 class="heading-1"><span>{{ __('homePage.bestseller') }}</span></h2>
                        @include('frontend.component.catalogue', [
                            'category' => $widgets[App\Enums\WidgetEnum::CATEGORY_MENU],
                        ])
                    </div>
                </div>
                <div class="panel-body">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-4">
                            <div class="best-seller-banner">
                                <a href="#" class="image img-cover"><img
                                        src="{{ $widgets[App\Enums\WidgetEnum::BESTSELLER]->album[0] ?? '' }}"
                                        alt="{{ $widgets[App\Enums\WidgetEnum::BESTSELLER]->album[0] ?? '' }}"></a>
                                <div class="banner-title">
                                    {!! $widgets[App\Enums\WidgetEnum::BESTSELLER]->description[$language] ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-large-3-4">
                            @if (isset($widgets[App\Enums\WidgetEnum::BESTSELLER]->object))
                                <div class="product-wrapper">
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach ($widgets[App\Enums\WidgetEnum::BESTSELLER]->object as $product)
                                                @foreach ($product->product_variants as $productVariant)
                                                    <div class="swiper-slide">
                                                        @include('frontend.component.product-item', [
                                                            'product' => $product,
                                                            'productVariant' => $productVariant,
                                                        ])
                                                    </div>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($widgets[App\Enums\WidgetEnum::FEATURED_NEWS]->object))
            <div class="panel-deal page-setup">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>{{ __('homePage.featured_news') }}</span></h2>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="uk-grid uk-grid-medium">
                            @foreach ($widgets[App\Enums\WidgetEnum::FEATURED_NEWS]->object as $key => $val)
                                <div class="uk-width-large-1-4">
                                    @include('frontend.component.news_item', [
                                        'news' => $val,
                                    ])
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- @if (isset($slides[App\Enums\SlideEnum::BANNER_FOOTER]['item']))
            <div class="uk-container uk-container-center">
                @php
                    $description = $slides[App\Enums\SlideEnum::BANNER_FOOTER]['item'][0]['description'];
                    $image = $slides[App\Enums\SlideEnum::BANNER_FOOTER]['item'][0]['image'];
                @endphp
                <div class="panel-group">
                    <div class="panel-body">
                        <div class="group-title">{!! $description !!}</div>
                        <div class="group-description">{{ $system['homepage_slogan'] }}</div>
                        <span class="image img-scaledowm"><img src="{{ $system['homepage_logo'] }}" alt=""></span>
                    </div>
                </div>
            </div>
        @endif --}}
        <div class="panel-commit">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="{{ asset('frontend/resources/img/commit-1.png') }}"
                                        alt="{{ __('homePage.commit.discount_title') }}"></span>
                                <div class="info">
                                    <div class="title">{{ __('homePage.commit.discount_title') }}</div>
                                    <div class="description">{{ __('homePage.commit.discount_description') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="{{ asset('frontend/resources/img/commit-2.png') }}"
                                        alt="{{ __('homePage.commit.free_shipping_title') }}"></span>
                                <div class="info">
                                    <div class="title">{{ __('homePage.commit.free_shipping_title') }}</div>
                                    <div class="description">{{ __('homePage.commit.free_shipping_description') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="{{ asset('frontend/resources/img/commit-3.png') }}"
                                        alt="{{ __('homePage.commit.account_discount_title') }}"></span>
                                <div class="info">
                                    <div class="title">{{ __('homePage.commit.account_discount_title') }}</div>
                                    <div class="description">{{ __('homePage.commit.account_discount_description') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="{{ asset('frontend/resources/img/commit-4.png') }}"
                                        alt="{{ __('homePage.commit.diverse_products_title') }}"></span>
                                <div class="info">
                                    <div class="title">{{ __('homePage.commit.diverse_products_title') }}</div>
                                    <div class="description">{{ __('homePage.commit.diverse_products_description') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="{{ asset('frontend/resources/img/commit-5.png') }}"
                                        alt="{{ __('homePage.commit.return_policy_title') }}"></span>
                                <div class="info">
                                    <div class="title">{{ __('homePage.commit.return_policy_title') }}</div>
                                    <div class="description">{{ __('homePage.commit.return_policy_description') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
