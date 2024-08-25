@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['show']['title']])

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-title">
                {{ __('form.menu_list') }}
            </div>
            <div class="panel-description">
                <p>+ {!! __('form.menu_list_description') !!}</p>
                <p>+ {!! __('form.menu_list_description_2') !!}</p>
                <p>+ {!! __('form.menu_list_description_3') !!}</p>
                <p>+ {!! __('form.menu_list_description_4') !!}</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <h5 style="margin: 0">{{ __('form.main_menu') }}</h5>
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
