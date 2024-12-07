@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['translate']['title']])

<form action="{{ route('menu.translate.save', ['languageId' => $languageId]) }}" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel-title">
                    {{ __('form.general_info') }}
                </div>
                <div class="panel-description">
                    <p class="align-justify">+ {!! __('form.menu_translate_description_1') !!}</p>
                    <p class="align-justify">+ {!! __('form.menu_translate_description_2') !!}</p>
                    <p class="align-justify">+ {!! __('form.menu_translate_description_3') !!}</p>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-title">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <h5 style="margin: 0">{{ __('form.translate_list') }}</h5>
                        </div>
                    </div>
                    <div class="ibox-content">
                        @if (count($menus))
                            @foreach ($menus as $key => $val)
                                @php
                                    $name = $val->languages->first()->pivot->name;
                                    $canonical = $val->languages->first()->pivot->canonical;
                                @endphp
                                <div class="menu-translate-item">
                                    <div class="row">
                                        <div class="col-lg-12 mb10">
                                            <div class="text-danger text-bold">Menu: {{ $val->position }}</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-row">
                                                <div class="uk-flex uk-flex-midle">
                                                    <div class="menu-name">{{ __('form.menu_name') }}</div>
                                                    <input type="text" value="{{ $name }}"
                                                        class="form-control" placeholder="" autocomplete="off" disabled>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="uk-flex uk-flex-midle">
                                                    <div class="menu-name">{{ __('form.path') }}</div>
                                                    <input type="text" value="{{ $canonical }}"
                                                        class="form-control" placeholder="" autocomplete="off" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-row">
                                                <input name="translate[name][]" type="text"
                                                    value="{{ $val->translate_name ?? translateContent($name) }}"
                                                    class="form-control" placeholder="{{ __('form.translate_input') }}"
                                                    autocomplete="off">
                                            </div>
                                            <div class="form-row">
                                                <input name="translate[canonical][]" type="text"
                                                    value="{{ $val->translate_canonical ?? translateContent($canonical) }}"
                                                    class="form-control" placeholder="{{ __('form.translate_input') }}"
                                                    autocomplete="off">
                                            </div>
                                            <input name="translate[id][]" type="hidden" value="{{ $val->id ?? '' }}"
                                                class="form-control" placeholder="{{ __('form.translate_input') }}"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>

</form>
