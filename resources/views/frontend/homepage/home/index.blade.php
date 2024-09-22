@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        @include('frontend.component.slide')
        <div class="panel-category page-setup">
            <div class="uk-container uk-container-center">
                @if (isset($widgets[App\Enums\WidgetEnum::CATEGORY_MENU]))
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle">
                            <h2 class="heading-1"><span>{{ __('homePage.product_category') }}</span></h2>
                            <div class="category-children">
                                <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                    @foreach ($widgets[App\Enums\WidgetEnum::CATEGORY_MENU] as $key => $val)
                                        @php
                                            $name = $val->languages->first()->pivot->name;
                                            $canonical = write_url(
                                                $val->languages->first()->pivot->canonical ?? '',
                                                true,
                                                true,
                                            );
                                        @endphp
                                        <li class="">
                                            <a href="{{ $canonical }}"
                                                title="{{ $name }}">{{ $name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($widgets[App\Enums\WidgetEnum::CATEGORY]))
                    <div class="panel-body">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach ($widgets[App\Enums\WidgetEnum::CATEGORY] as $key => $val)
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
                                            <div class="total-product">{{ $productCount }} {{ __('unit.product') }}</div>
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
        {{--
        <div class="panel-popular">
            <div class="uk-container uk-container-center">
                <div class="panel-head">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h2 class="heading-1"><span>Sản phẩm nổi bật</span></h2>
                        <div class="category-children">
                            <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                <li class=""><a href="" title="">Tất cả</a></li>
                                <li class=""><a href="" title="">Bánh & Sữa</a></li>
                                <li class=""><a href="" title="">Cà phê & Trà</a></li>
                                <li class=""><a href="" title="">Thức ăn cho vật nuôi</a></li>
                                <li class=""><a href="" title="">Rau củ</a></li>
                                <li class=""><a href="" title="">Hoa Quả</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="uk-grid uk-grid-medium">
                        <?php for($i = 0; $i<=14; $i++){  ?>
                        <div class="uk-width-large-1-5 mb20">
                            @include('frontend.component.product-item')
                        </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-bestseller">
            <div class="uk-container uk-container-center">
                <div class="panel-head">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h2 class="heading-1"><span>Sản phẩm bán chạy</span></h2>
                        <div class="category-children">
                            <ul class="uk-list uk-clearfix uk-flex uk-flex-middle">
                                <li class=""><a href="" title="">Tất cả</a></li>
                                <li class=""><a href="" title="">Bánh & Sữa</a></li>
                                <li class=""><a href="" title="">Cà phê & Trà</a></li>
                                <li class=""><a href="" title="">Thức ăn cho vật nuôi</a></li>
                                <li class=""><a href="" title="">Rau củ</a></li>
                                <li class=""><a href="" title="">Hoa Quả</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="uk-grid uk-grid-medium">
                        <div class="uk-width-large-1-4">
                            <div class="best-seller-banner">
                                <a href="" class="image img-cover"><img src="resources/img/bestseller.png"
                                        alt=""></a>
                                <div class="banner-title">Bring Natural<br> Into Your<br> Home</div>
                            </div>
                        </div>
                        <div class="uk-width-large-3-4">
                            <div class="product-wrapper">
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php for($i = 0; $i < count($category); $i++){  ?>
                                        <div class="swiper-slide">
                                            @include('frontend.component.product-item')
                                        </div>
                                        <?php }  ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-deal page-setup">
            <div class="uk-container uk-container-center">
                <div class="panel-head">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h2 class="heading-1"><span>Giảm giá trong ngày</span></h2>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="uk-grid uk-grid-medium">
                        <?php for($i = 0; $i<=3; $i++){  ?>
                        <div class="uk-width-large-1-4">
                            @include('frontend.component.product-item-2')
                        </div>
                        <?php }  ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="uk-container uk-container-center">
            <div class="panel-group">
                <div class="panel-body">
                    <div class="group-title">Stay home & get your daily <br> needs from our shop</div>
                    <div class="group-description">Start Your Daily Shopping with Nest Mart</div>
                    <span class="image img-scaledowm"><img src="resources/img/banner-9-min.png" alt=""></span>
                </div>
            </div>
        </div>
        <div class="panel-commit">
            <div class="uk-container uk-container-center">
                <div class="uk-grid uk-grid-medium">
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="resources/img/commit-1.png" alt=""></span>
                                <div class="info">
                                    <div class="title">Giá ưu đãi</div>
                                    <div class="description">Khi mua từ 500.000đ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="resources/img/commit-2.png" alt=""></span>
                                <div class="info">
                                    <div class="title">Miễn phí vận chuyển</div>
                                    <div class="description">Trong bán kính 2km</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="resources/img/commit-3.png" alt=""></span>
                                <div class="info">
                                    <div class="title">Ưu đãi</div>
                                    <div class="description">Khi đăng ký tài khoản</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="resources/img/commit-4.png" alt=""></span>
                                <div class="info">
                                    <div class="title">Đa dạng </div>
                                    <div class="description">Sản phẩm đa dạng</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-5">
                        <div class="commit-item">
                            <div class="uk-flex uk-flex-middle">
                                <span class="image"><img src="resources/img/commit-5.png" alt=""></span>
                                <div class="info">
                                    <div class="title">Đổi trả </div>
                                    <div class="description">Đổi trả trong ngày</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        --}}
    </div>
@endsection
