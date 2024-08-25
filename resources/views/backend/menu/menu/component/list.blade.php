<div class="row">
    <div class="col-lg-5">
        <div class="ibox">
            <div class="ibox-content">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    {{ __('form.self_generated_link') }}
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="panel-title">
                                    {{ __('form.create_menu') }}
                                </div>
                                <div class="panel-description">
                                    <p>+ {{ __('form.create_menu_description_1') }}</p>
                                    <p>
                                        <small class="text-danger">* {{ __('form.create_menu_description_2') }}</small>
                                    </p>
                                    <p>
                                        <small class="text-danger">* {{ __('form.create_menu_description_3') }}</small>
                                    </p>
                                    <p>
                                        <small class="text-danger">* {{ __('form.create_menu_description_4') }}</small>
                                    </p>
                                    <a style="color: #000; border-color: #c4cdd5; display: inline-block !important"
                                        href="" title=""
                                        class="btn btn-default add-menu m-b m-r right">{{ __('form.add_link') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach (__('module.model') as $key => $val)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapse menu-module" data-toggle="collapse" data-parent="#accordion"
                                        href="#{{ $key }}"
                                        data-model="{{ $key }}">{{ $val }}</a>
                                </h4>
                            </div>
                            <div id="{{ $key }}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <input type="text" value="" class="form-control search-menu" name="keyword"
                                        placeholder="{{ __('form.minimum_input_length') }}" autocomplete="off">
                                    <div class="menu-list mt20">

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">{{ __('form.menu_name') }}</label>
                    </div>
                    <div class="col-lg-4">
                        <label for="">{{ __('form.path') }}</label>
                    </div>
                    <div class="col-lg-2">
                        <label for="">{{ __('form.location') }}</label>
                    </div>
                    <div class="col-lg-2 text-center">
                        <label for="">{{ __('form.delete') }}</label>
                    </div>
                </div>
                <div class="hr-line-dashed" style="margin: 10px 0"></div>
                <div class="menu-wrapper">
                    @php
                        $menu = old('menu', $menuList ?? null);
                    @endphp
                    <div class="notification text-center {{ is_array($menu) && count($menu) ? 'none' : '' }}">
                        <h4 style="font-weight: 500; font-size: 16px; color: #000">
                            {{ __('form.not_link') }}
                        </h4>
                        <p style="color: #555; margin-top: 10px">
                            {!! __('form.action_link') !!}
                        </p>
                    </div>
                    @if (is_array($menu) && count($menu))
                        @foreach ($menu['name'] as $key => $val)
                            <div class="row mb10 menu-item {{ $menu['canonical'][$key] }}">
                                <div class="col-lg-4">
                                    <input type="text" value="{{ $val }}" class="form-control"
                                        name="menu[name][]">
                                </div>
                                <div class="col-lg-4">
                                    <input type="text" value="{{ $menu['canonical'][$key] }}" class="form-control"
                                        name="menu[canonical][]">
                                </div>
                                <div class="col-lg-2">
                                    <input type="text" value="{{ $menu['order'][$key] }}"
                                        class="form-control int text-right" name="menu[order][]">
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-row text-center">
                                        <a class="delete-menu">
                                            <img src="backend/img/remove.png">
                                        </a>
                                    </div>
                                    <input type="text" class="hidden" name="menu[id][]"
                                        value="{{ $menu['id'][$key] }}">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
