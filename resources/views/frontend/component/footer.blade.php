<footer class="footer">
    <div class="uk-container uk-container-center">
        <div class="footer-upper">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    <div class="footer-contact">
                        <a href="" class="image img-scaledown"><img
                                src="https://themepanthers.com/wp/nest/d1/wp-content/uploads/2022/02/logo.png"
                                alt=""></a>
                        <div class="footer-slogan">Awesome grocery store website template</div>
                        <div class="company-address">
                            <div class="address">Số 16 Ngõ 198 Lê Trọng Tấn, Khương Mai, Thanh Xuân, Hà Nội</div>
                            <div class="phone">Hotline: 0988.778.688</div>
                            <div class="email">Email: info@nestmart.com</div>
                            <div class="hour">Giờ làm việc: 10:00 - 18:00, Mon - Sat</div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-3-5">
                    <div class="footer-menu">
                        @if (isset($menus[App\Enums\MenuEnum::FOOTER_MENU]))
                            <div class="uk-grid uk-grid-medium">
                                @php
                                    $classFooterItem =
                                        'uk-width-large-1-' . count($menus[App\Enums\MenuEnum::FOOTER_MENU]);
                                @endphp
                                @foreach ($menus[App\Enums\MenuEnum::FOOTER_MENU] as $key => $val)
                                    <div class="footer-menu-item {{ $classFooterItem }}">
                                        <div class="ft-menu">
                                            <div class="heading">{{ $val['item']->languages->first()->pivot->name }}
                                            </div>
                                            <ul class="uk-list uk-clearfix">
                                                @foreach ($val['children'] as $keyChildren => $valChildren)
                                                    @php
                                                        $name = $valChildren['item']->languages->first()->pivot->name;
                                                        $canonical = write_url(
                                                            $valChildren['item']->languages->first()->pivot
                                                                ->canonical ?? '',
                                                            true,
                                                            true,
                                                        );
                                                    @endphp
                                                    <li>
                                                        <a href="{{ $canonical }}">{{ $name }}<a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="uk-width-large-1-5">
                    <div class="fanpage-facebook">
                        <div class="ft-menu">
                            <div class="heading">Fanpage Facebook</div>
                            <div class="fanpage">
                                <div class="fb-page" data-href="https://www.facebook.com/tonyit2003" data-tabs=""
                                    data-width="" data-height="" data-small-header="false"
                                    data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                                    <blockquote cite="https://www.facebook.com/tonyit2003"
                                        class="fb-xfbml-parse-ignore">
                                        <a href="https://www.facebook.com/tonyit2003">Facebook</a>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="uk-container uk-container-center">
            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                <div class="copyright-text">© 2023, HT Web VietNam.<br> All rights reserved</div>
                <div class="copyright-contact">
                    <div class="uk-flex uk-flex-middle">
                        <div class="phone-item">
                            <div class="p">Hotline: 09823 65 824</div>
                            <div class="worktime">Làm việc: 8:00 - 22:00</div>
                        </div>
                        <div class="phone-item">
                            <div class="p">Support: 0965 763 389</div>
                            <div class="worktime">Hỗ trợ 24/7</div>
                        </div>
                    </div>
                </div>
                <div class="social">
                    <div class="uk-flex uk-flex-middle">
                        <div class="span">Follow us:</div>
                        <div class="social-list">
                            <div class="uk-flex uk-flex-middle">
                                <a href="" class=""><i class="fa fa-facebook"></i></a>
                                <a href="" class=""><i class="fa fa-twitter"></i></a>
                                <a href="" class=""><i class="fa fa-skype"></i></a>
                                <a href="" class=""><i class="fa fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
