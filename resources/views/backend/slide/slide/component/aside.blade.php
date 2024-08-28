<div class="ibox slide-setting slide-normal">
    <div class="ibox-title">
        <h5>{{ __('form.basic_settings') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.slide_name') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $slide->name ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.keyword') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="keyword" value="{{ old('keyword', $slide->keyword ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slide-setting">
                    <div class="setting-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="setting-text">{{ __('form.width') }}</span>
                            <div class="setting-value">
                                <input type="text" name="setting[width]" class="form-control" value="0">
                                <span class="px">{{ __('unit.px') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="setting-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="setting-text">{{ __('form.height') }}</span>
                            <div class="setting-value">
                                <input type="text" name="setting[height]" class="form-control" value="0">
                                <span class="px">{{ __('unit.px') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="setting-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="setting-text">{{ __('form.effect') }}</span>
                            <div class="setting-value">
                                <select name="setting[animation]" id="" class="form-control setupSelect2">
                                    @foreach (__('module.effect') as $key => $val)
                                        <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="setting-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="setting-text">{{ __('form.arrow') }}</span>
                            <div class="setting-value">
                                <input type="checkbox" name="setting[arrow]" value="accept" checked>
                            </div>
                        </div>
                    </div>
                    <div class="setting-item">
                        <div class="uk-flex uk-flex-middle">
                            <span class="setting-text">{{ __('form.navigation_bar') }}</span>
                            <div class="setting-value">
                                @foreach (__('module.navigate') as $key => $val)
                                    <div class="nav-setting-item uk-flex uk-flex-middle">
                                        <input type="radio" name="setting[navigate]" value="{{ $key }}"
                                            id="navigate_{{ $key }}"
                                            {{ $key === old('setting.navigate', 'dots') ? 'checked' : '' }}>
                                        <label for="navigate_{{ $key }}">{{ $val }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox slide-setting slide-advance">
    <div class="ibox-title uk-flex uk-flex-middle uk-flex-space-between">
        <h5>{{ __('form.advanced_settings') }}</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>

    </div>
    <div class="ibox-content">
        <div class="setting-item">
            <div class="uk-flex uk-flex-middle">
                <span class="setting-text">{{ __('form.auto_play') }}</span>
                <div class="setting-value">
                    <input type="checkbox" name="setting[autoplay]" value="accept">
                </div>
            </div>
        </div>
        <div class="setting-item">
            <div class="uk-flex uk-flex-middle">
                <span class="setting-text">{{ __('form.hover_pause') }}</span>
                <div class="setting-value">
                    <input type="checkbox" name="setting[pauseHover]" value="accept">
                </div>
            </div>
        </div>
        <div class="setting-item">
            <div class="uk-flex uk-flex-middle">
                <span class="setting-text">{{ __('form.photo_transfer_time') }}</span>
                <div class="setting-value">
                    <input type="text" name="setting[animationDelay]" class="form-control">
                    <span class="px">{{ __('unit.ms') }}</span>
                </div>
            </div>
        </div>
        <div class="setting-item">
            <div class="uk-flex uk-flex-middle">
                <span class="setting-text">{{ __('form.effect_speed') }}</span>
                <div class="setting-value">
                    <input type="text" name="setting[animationSpeed]" class="form-control">
                    <span class="px">{{ __('unit.ms') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox short-code">
    <div class="ibox-title ">
        <h5>{{ __('form.short_code') }}</h5>
    </div>
    <div class="ibox-content">
        <textarea name="short_code" id="" class="textarea form-control"></textarea>
    </div>
</div>
