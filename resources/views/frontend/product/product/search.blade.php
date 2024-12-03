@extends('frontend.homepage.layout')
@section('content')
    <div class="product-catalogue page-wrapper">
        <div class="uk-container uk-container-center">
            <div class="page-breadcrumb background">
                <ul class="uk-list uk-clearfix">
                    <li>
                        <a href="{{ config('app.url') }}">
                            <i class="fi-rs-home mr5"></i>
                            {{ __('userPage.home_page') }}
                        </a>
                    </li>
                    <li style="color: #fff">
                        {{ __('info.search_result_for') }} '{{ $keyword }}'
                    </li>
                </ul>
            </div>

            <div class="panel-body">
                {{-- @include('frontend.product.catalogue.component.filter')
                @include('frontend.product.catalogue.component.filterContent') --}}
                @if (isset($productVariants))
                    <div class="product-list">
                        <div class="uk-grid uk-grid-medium">
                            @foreach ($productVariants as $key => $val)
                                <div class="uk-width-1-2 uk-width-small-1-2 uk-width-medium-1-3 uk-width-large-1-5 mb20">
                                    @include('frontend.component.product-variant-item', [
                                        'productVariant' => $val,
                                    ])
                                </div>
                            @endforeach
                        </div>
                        <div class="uk-flex uk-flex-center">
                            {{ $productVariants->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
