@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['show']['title']])

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="uk-flex uk-flex-middle mb20">
                @php
                    $selectLanguage = $languageCurrent;
                @endphp
                @foreach ($allLanguages as $language)
                    @php
                        $url =
                            $language->id == $languageCurrent
                                ? route('menu.edit', ['id' => $id])
                                : route('menu.translate', ['languageId' => $language->id, 'id' => $id]);
                    @endphp
                    <a class="image img-cover system-flag language-item {{ $language->id == $languageCurrent ? 'active' : '' }}"
                        href="{{ $url }}">
                        <img src="{{ $language->image }}" alt="">
                    </a>
                @endforeach
            </div>
            <div class="panel-title">
                {{ __('form.menu_list') }}
            </div>
            <div class="panel-description">
                <p class="align-justify">+ {!! __('form.menu_list_description') !!}</p>
                <p class="align-justify">+ {!! __('form.menu_list_description_2') !!}</p>
                <p class="align-justify">+ {!! __('form.menu_list_description_3') !!}</p>
                <p class="align-justify">+ {!! __('form.menu_list_description_4') !!}</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5 style="margin: 0">{{ $menuCatalogue->name }}</h5>
                        <a href="{{ route('menu.editMenu', ['id' => $id]) }}"
                            class="custom-button">{{ __('form.update_menu') }}</a>
                    </div>
                </div>
                <div class="ibox-content" id="dataCatalogue" data-catalogueId="{{ $id }}">
                    @php
                        $menus = recursive($menus);
                        $menuString = recursive_menu($menus);
                    @endphp
                    @if (count($menus))
                        <div class="dd" id="nestable2">
                            <ol class="dd-list">
                                {!! $menuString !!}
                            </ol>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
