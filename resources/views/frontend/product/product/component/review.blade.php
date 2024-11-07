@php
    $variant_uuid = $modelVariant->uuid;
    $modelName = $model->name . ' - ' . $modelVariant->languages->first()->pivot->name;
    $modelImage =
        isset($modelVariant->album) && $modelVariant->album != ''
            ? explode(',', $modelVariant->album)[0]
            : (isset($model->album)
                ? json_decode($model->album, true)[0]
                : 'backend/img/no-photo.png');

    $totalReview = isset($reviews) ? $reviews->count() : 0;
    $totalRate = isset($reviews) ? number_format($reviews->avg('score'), 1) : 0;
    $starPercent = isset($reviews) ? ($totalRate / 5) * 100 : 0;
@endphp

<div class="review-container">
    <div class="panel-head">
        <h2 class="review-heading">
            {{ __('info.product_review') }}
        </h2>
        <div class="review-statistic">
            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle">
                <div class="uk-width-large-1-3">
                    <div class="review-averate review-item">
                        <div class="title">{{ __('info.average_review') }}</div>
                        <div class="score">{{ $totalRate }}/5</div>
                        <div class="star-rating" style="--star-width: {{ $starPercent }}%">
                            <div class="stars"></div>
                        </div>
                        {{-- <div class="star">
                            @for ($i = 0; $i <= 4; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div> --}}
                        <div class="total-rate">{{ $totalReview }} {{ __('unit.evaluate') }}</div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="progress-block review-item">
                        @for ($i = 5; $i >= 1; $i--)
                            @php
                                $countStar = isset($reviews) ? $reviews->where('score', $i)->count() : 0;
                                $progressPercent = $totalReview != 0 ? ($countStar / $totalReview) * 100 : 0;
                            @endphp
                            <div class="progress-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="text">{{ $i }}</span>
                                    <i class="fa fa-star"></i>
                                    <div class="uk-progress">
                                        <div class="uk-progress-bar" style="width: {{ $progressPercent }}%;"></div>
                                    </div>
                                    <span class="text">{{ $countStar }}</span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="review-action review-item">
                        <div class="text">{{ __('info.have_used_product') }}</div>
                        @if (Auth::guard('customers')->check())
                            <button class="btn btn-review" data-uk-modal="{ target:'#review' }">
                                {{ __('button.submit_review') }}
                            </button>
                        @else
                            <button class="btn btn-review" data-uk-modal="{ target:'#loginNow' }">
                                {{ __('button.submit_review') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div class="review-filter">
            <div class="uk-flex uk-flex-middle">
                <span class="filter-text">{{ __('info.filter_by') }}: </span>
                <div class="filter-item">
                    <span>{{ __('info.purchased') }}</span>
                    <span>5 {{ __('unit.star') }}</span>
                    <span>4 {{ __('unit.star') }}</span>
                    <span>3 {{ __('unit.star') }}</span>
                    <span>2 {{ __('unit.star') }}</span>
                    <span>1 {{ __('unit.star') }}</span>
                </div>
            </div>
        </div>
        <div class="review-wrapper">
            @if (isset($reviews))
                @foreach ($reviews as $review)
                    @php
                        $firstLetterOfName = $review->fullname ? getFirstLetterOfName($review->fullname) : 'A';
                        $fullname = $review->fullname ?? __('info.anonymous_customer');
                        $content = $review->content;
                        $create_at = convertDateTime($review->updated_at);
                    @endphp
                    <div class="review-block-item">
                        <div class="review-general uk-clearfix">
                            <div class="review-avatar">
                                <span class="shae">{{ $firstLetterOfName }}</span>
                            </div>
                            <div class="review-content-block">
                                <div class="review-content">
                                    <div class="name uk-flex uk-flex-middle">
                                        <span>{{ $fullname }}</span>
                                        <span class="review-buy">
                                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                                            {{ __('info.purchased_at') }} {{ $system['homepage_brand'] }}
                                        </span>
                                    </div>
                                    {!! generateStar($review->score) !!}
                                    <div class="description">
                                        {{ $content }}
                                    </div>
                                    <div class="review-toolbox">
                                        <div class="uk-flex uk-flex-middle">
                                            <div class="created_at">{{ __('info.day') }} {{ $create_at }}</div>
                                            {{-- <div class="review-reply">{{ __('info.reply') }}</div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="review-block-item uk-clearfix reply-block">
                        <div class="review-avatar">
                            <span class="shae">AD</span>
                        </div>
                        <div class="review-content-block">
                            <div class="review-content">
                                <div class="name uk-flex uk-flex-middle">
                                    <span>Admin</span>
                                    <span class="review-buy">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        {{ __('info.purchased_at') }} {{ $system['homepage_brand'] }}
                                    </span>
                                </div>
                                <div class="review-star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="description">
                                    Dạ TCShop xin chào
                                    Với tình trạng máy này mình vui lòng mang đến cửa hàng thẩm định báo giá nếu có thu
                                    nhé
                                    Rất tiếc ưu đãi HSSV chưa áp dụng với Iphone ạ
                                    APPLE IPHONE 15 128GB HỒNG CHÍNH HÃNG VN/A giá thời điểm hiện tại: 19.690.000 tại
                                    miền
                                    nam
                                    Sản phẩm có hỗ trợ trả góp qua thẻ tín dụng trong kỳ hạn 3, 6 tháng 0% lãi suất,
                                    không
                                    phí chuyển đổi, không trả trước (thẻ đủ hạn mức)
                                    Mình góp trong 6 tháng mỗi tháng góp: 3.282.000
                                    Ngoài ra sản phẩm có hỗ trợ trả góp qua Công ty tài chính, Momo Apple, Kredivo và
                                    Fundiin ạ
                                    Dạ mình ở khu vực nào để em kiểm tra shop còn hàng gần mình nhất ạ
                                    Thân mến.
                                </div>
                                <div class="review-toolbox">
                                    <div class="uk-flex uk-flex-middle">
                                        <div class="created_at">Ngày 09/04/2003</div>
                                        <div class="review-reply">{{ __('info.reply') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                @endforeach
            @endif
        </div>
    </div>
</div>

<div id="review" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="review-popup-wrapper">
            <div class="panel-head">{{ __('info.product_review') }}</div>
            <div class="panel-body">
                <div class="product-preview">
                    <span class="image img-scaledown">
                        <img src="{{ $modelImage }}" alt="{{ $modelName }}">
                    </span>
                    <div class="product-title uk-text-center">{{ $modelName }}</div>
                    <div class="popup-rating uk-clearfix uk-text-center">
                        <div class="rate uk-clearfix">
                            <input type="radio" id="star5" name="rate" class="rate" value="5" checked />
                            <label for="star5" title="{{ __('info.great') }}">5 {{ __('unit.star') }}</label>

                            <input type="radio" id="star4" name="rate" class="rate" value="4" />
                            <label for="star4" title="{{ __('info.satisfied') }}">4 {{ __('unit.star') }}</label>

                            <input type="radio" id="star3" name="rate" class="rate" value="3" />
                            <label for="star3" title="{{ __('info.normal') }}">3 {{ __('unit.star') }}</label>

                            <input type="radio" id="star2" name="rate" class="rate" value="2" />
                            <label for="star2" title="{{ __('info.okay') }}">2 {{ __('unit.star') }}</label>

                            <input type="radio" id="star1" name="rate" class="rate" value="1" />
                            <label for="star1" title="{{ __('info.dislike') }}">1 {{ __('unit.star') }}</label>
                        </div>
                        <div class="rate-text">
                            {{ __('info.great') }}
                        </div>
                    </div>
                    <div class="review-form">
                        <div class="uk-form form">
                            <div class="form-row">
                                <textarea name="" id="" class="review-textarea"
                                    placeholder="{{ __('info.share_thoughts_product') }}..."></textarea>
                            </div>
                            {{-- <div class="form-row">
                                <div class="uk-flex uk-flex-middle">
                                    <div class="gender-item uk-flex uk-flex-middle">
                                        <input type="radio" name="gender" value="male" id="male" checked>
                                        <label for="male">{{ __('info.male') }}</label>
                                    </div>
                                    <div class="gender-item uk-flex uk-flex-middle">
                                        <input type="radio" name="gender" value="female" id="female">
                                        <label for="female">{{ __('info.female') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid uk-grid-medium">
                                <div class="uk-width-large-1-2">
                                    <div class="form-row">
                                        <input type="text" name="fullname" value="" class="review-text"
                                            placeholder="{{ __('form.enter_name') }}">
                                    </div>
                                </div>
                                <div class="uk-width-large-1-2">
                                    <div class="form-row">
                                        <input type="text" name="phone" value="" class="review-text"
                                            placeholder="{{ __('form.enter_phone') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <input type="text" name="email" value="" class="review-text"
                                    placeholder="{{ __('form.enter_email') }}">
                            </div> --}}
                            <div class="uk-text-center">
                                <button type="submit" value="send" class="btn-send-review"
                                    name="create">{{ __('button.complete') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" class="variant_uuid" value="{{ $variant_uuid }}">

<div id="loginNow" class="uk-modal">
    <div class="uk-modal-dialog">
        <a class="uk-modal-close uk-close"></a>
        <div class="review-popup-wrapper">
            <div class="panel-head">{{ __('info.notification') }}</div>
            <div class="panel-body">
                <div class="">
                    <p class="uk-text-center">{{ __('info.login_to_review') }}</p>
                </div>
                <div class="uk-margin-top">
                    <a href="{{ route('authClient.index') }}"
                        class="uk-button uk-button-primary uk-width-1-2 uk-margin-small-right" id="loginButton">
                        {{ __('button.login') }}
                    </a>
                    <a href="{{ route('authClient.register') }}" class="uk-button uk-button-secondary uk-width-1-2"
                        id="registerButton">
                        {{ __('button.register') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
