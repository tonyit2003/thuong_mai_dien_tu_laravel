@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-category">
            <div class="uk-container uk-container-center">
                @include('frontend.component.breadcrumb', [
                    'model' => $postCatalogue,
                    'breadcrumb' => $breadcrumb,
                ])
                <div style="max-width: 1200px; " class="uk-container-center post-container">
                    <h1>{!! $post->name !!}</h1>
                    {!! $post->description !!}
                    {!! $post->content !!}
                </div>
            </div>
        </div>
        @if (isset($widgets[App\Enums\WidgetEnum::FEATURED_NEWS]->object))
            <div class="panel-deal">
                <div class="uk-container uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>{{ __('homePage.related_articles') }}</span></h2>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="swiper-container deal-container">
                            <div class="swiper-wrapper">
                                @foreach ($widgets[App\Enums\WidgetEnum::FEATURED_NEWS]->object as $key => $val)
                                    <div class="swiper-slide">
                                        @include('frontend.component.news_item', [
                                            'news' => $val,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next-post deal-next"></div>
                            <div class="swiper-button-prev-post deal-prev"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
