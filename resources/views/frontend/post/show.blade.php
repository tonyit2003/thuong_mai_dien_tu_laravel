@extends('frontend.homepage.layout')

@section('content')
    <div id="homepage" class="homepage">
        <div class="panel-category">
            <div class="uk-container uk-container-center">
                @if (isset($breadcrumb))
                    @include('frontend.component.breadcrumb', [
                        'model' => $postCatalogue,
                        'breadcrumb' => $breadcrumb,
                    ])
                @else
                    <div class="page-breadcrumb background">
                        <h1 class="heading-2">
                            <span style="color: #fff">
                                {{ __('homePage.post') }}
                            </span>
                        </h1>
                        <ul class="uk-list uk-clearfix">
                            <li>
                                <a href="{{ config('app.url') }}">
                                    <i class="fi-rs-home mr5"></i>
                                    {{ __('userPage.home_page') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ 'bai-viet.html' }}" title="{{ __('homePage.post') }}">
                                    {{ __('homePage.post') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
                <div style="max-width: 1610px;" class="uk-container-center">
                    <div class="panel-head">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h2 class="heading-1"><span>{{ $posts->isEmpty() ? __('homePage.no_post') : __('homePage.featured_news') }}</span></h2>
                        </div>
                    </div>
                    @foreach ($posts as $key => $val)
                        <div class="post-item uk-grid uk-grid-small uk-grid-match"
                            style="margin-bottom: 30px; padding: 15px; border-bottom: 1px solid #ccc;">
                            <!-- Hình ảnh -->
                            <div class="post-image uk-width-1-3">
                                <a href="{{ $val->canonical . '.html' }}" style="text-decoration: none; color: inherit;"
                                    class="image img-scaledown img-zoomin">
                                    <img src="{{ $val->image }}" alt="" style="max-width: 100%; height: auto; border-radius: 10px;">
                                </a>
                            </div>
                            <!-- Thông tin bài viết -->
                            <div class="post-info uk-width-2-3" style="padding-left: 20px; display: flex; flex-direction: column;">
                                <h2 style="margin: 0; font-size: 26px; line-height: 1.5;">
                                    <a href="{{ $val->canonical . '.html' }}" style="text-decoration: none; color: inherit;">
                                        {!! $val->name !!}
                                    </a>
                                </h2>
                                <p style="line-height: 1.5; margin-top: 5px; margin-bottom: 0;">
                                    {!! $val->description !!}
                                </p>
                            </div>
                        </div>
                    @endforeach
                    <div style="text-align: center">
                        {{ $posts->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
