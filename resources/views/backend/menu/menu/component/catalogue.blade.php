<div class="row">
    <div class="col-lg-5">
        <div class="panel-head">
            <div class="panel-title">
                {{ __('form.menu_location') }}
            </div>
            <div class="panel-description">
                <p>{{ __('form.menu_location_description') }}</p>
                <p>{{ __('form.menu_location_description_2') }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-12 mb10">
                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                            <div class="text-bold">
                                {{ __('form.menu_location_choose') }}
                                <span class="text-danger">(*)</span>
                            </div>
                            <button data-toggle="modal" data-target="#createMenuCatalogue" type="button" name=""
                                class="createMenuCatalogue btn btn-danger">
                                {{ __('button.create_menu_location') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <select name="menu_catalogue_id" id="" class="setupSelect2">
                            <option value="0">[{{ __('form.menu_location_choose') }}]</option>
                            @if (count($menuCatalogues))
                                @foreach ($menuCatalogues as $key => $val)
                                    <option
                                        {{ isset($menuCatalogue) && $menuCatalogue->id == $val->id ? 'selected' : '' }}
                                        value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
