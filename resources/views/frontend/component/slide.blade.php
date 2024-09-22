@if (isset($slides[App\Enums\SlideEnum::MAIN_SLIDE]['item']))
    <div class="panel-slide page-setup"
        data-setting="{{ json_encode($slides[App\Enums\SlideEnum::MAIN_SLIDE]['setting']) }}">
        <div class="uk-container uk-container-center">
            <div class="swiper-container">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-wrapper">
                    @foreach ($slides[App\Enums\SlideEnum::MAIN_SLIDE]['item'] as $key => $val)
                        <div class="swiper-slide">
                            <div class="slide-item">
                                <div class="slide-overlay">
                                    <div class="slide-title">{!! $val['name'] !!}</div>
                                    <div class="slide-description">{!! $val['description'] !!} </div>
                                </div>
                                {{-- <div class="subcribe-form">
                                    <form action="" class="uk-form form">
                                        <input type="text" name="email" value="" class="input-text"
                                            placeholder="Nhập vào Email Của bạn">
                                        <button type="submit" name="submit" class="btn-send">Subcribe</button>
                                    </form>
                                </div> --}}
                                <span class="image">
                                    <img src="{{ $val['image'] }}" alt="{{ $val['name'] }}">
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
@endif
