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
                        <div class="score">5/5</div>
                        <div class="star">
                            @for ($i = 0; $i <= 4; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                        </div>
                        <div class="total-rate">943 {{ __('unit.evaluate') }}</div>
                    </div>
                </div>
                <div class="uk-width-large-1-3">
                    <div class="progress-block review-item">
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="progress-item">
                                <div class="uk-flex uk-flex-middle">
                                    <span class="text">{{ $i }}</span>
                                    <i class="fa fa-star"></i>
                                    <div class="uk-progress">
                                        <div class="uk-progress-bar" style="width: 40%;"></div>
                                    </div>
                                    <span class="text">113</span>
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
            @for ($i = 0; $i < 10; $i++)
                <div class="review-block-item">
                    <div class="review-general uk-clearfix">
                        <div class="review-avatar">
                            <span class="shae">LHT</span>
                        </div>
                        <div class="review-content-block">
                            <div class="review-content">
                                <div class="name uk-flex uk-flex-middle">
                                    <span>Lê Hữu Tài</span>
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
                                    em đang là sinh viên em đang dùng Oppo f11 pro hiện máy em đang chảy mực và em muốn
                                    đổi qua iPhone em muốn hỏi thử nếu là sinh viên có được giảm giá không ạ nếu góp thì
                                    trả trước bao nhiêu ạ
                                </div>
                                <div class="review-toolbox">
                                    <div class="uk-flex uk-flex-middle">
                                        <div class="created_at">Ngày 09/04/2003</div>
                                        <div class="review-reply">{{ __('info.reply') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="review-block-item uk-clearfix reply-block">
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
                                Với tình trạng máy này mình vui lòng mang đến cửa hàng thẩm định báo giá nếu có thu nhé
                                Rất tiếc ưu đãi HSSV chưa áp dụng với Iphone ạ
                                APPLE IPHONE 15 128GB HỒNG CHÍNH HÃNG VN/A giá thời điểm hiện tại: 19.690.000 tại miền
                                nam
                                Sản phẩm có hỗ trợ trả góp qua thẻ tín dụng trong kỳ hạn 3, 6 tháng 0% lãi suất, không
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
                </div>
            @endfor
        </div>
    </div>
</div>
