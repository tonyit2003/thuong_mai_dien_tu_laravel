<footer class="footer">
    <div class="uk-container uk-container-center">
        <div class="footer-upper">
            <div class="uk-grid uk-grid-medium">
                <div class="uk-width-large-1-5">
                    <div class="footer-contact">
                        <a href="" class="image img-scaledown"><img src="{{ $system['homepage_logo'] }}" alt=""></a>
                        <div class="footer-slogan">Slogan: {{ $system['homepage_slogan'] }}</div>
                        <div class="company-address">
                            <div class="address">{{ __('configuration.address') }}: {{ $system['contact_address'] }}</div>
                            <div class="phone">Hotline: {{ $system['contact_hotline'] }}</div>
                            <div class="email">Email: {{ $system['contact_email'] }}</div>
                            <div class="worktime">{{ __('configuration.worktime') }}: 8:00 - 22:00</div>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-3-5">
                    <div class="footer-menu">
                        @if (isset($menus[App\Enums\MenuEnum::FOOTER_MENU]))
                            <div class="uk-grid uk-grid-medium">
                                @php
                                    $classFooterItem = 'uk-width-large-1-' . count($menus[App\Enums\MenuEnum::FOOTER_MENU]);
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
                                                            $valChildren['item']->languages->first()->pivot->canonical ?? '',
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
                                <div class="fb-page" data-href="https://www.facebook.com/tonyit2003" data-tabs="" data-width="" data-height=""
                                    data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                                    <blockquote cite="https://www.facebook.com/tonyit2003" class="fb-xfbml-parse-ignore">
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
                <div class="copyright-text">{{ $system['homepage_copyright'] }}©<br> All rights reserved</div>
                <div class="copyright-contact">
                    <div class="uk-flex uk-flex-middle">
                        <div class="phone-item">
                            <div class="p">Hotline: {{ $system['contact_hotline'] }}</div>
                            <div class="worktime">{{ __('configuration.worktime') }}: 8:00 - 22:00</div>
                        </div>
                        <div class="phone-item">
                            <div class="p">{{ __('configuration.support') }}: {{ $system['contact_sell_phone'] }}</div>
                            <div class="worktime">{{ __('configuration.support') }} 24/7</div>
                        </div>
                    </div>
                </div>
                <div class="social">
                    <div class="uk-flex uk-flex-middle">
                        <div class="span">{{ __('configuration.followus') }}:</div>
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
