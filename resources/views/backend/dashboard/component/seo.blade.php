<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('seo_configuration.title') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('meta_title', $model->meta_title ?? __('seo_configuration.default_title')) }}
            </div>
            <div class="canonical">
                {{ config('app.url') . old('canonical', $model->canonical ?? __('seo_configuration.canonical')) . config('apps.general.suffix') }}
            </div>
            <div class="meta-description">
                {{ old('meta_description', $model->meta_description ?? __('seo_configuration.default_description')) }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>
                                    {{ __('form.seo_title') }}
                                </span>
                                <span class="count_meta_title">
                                    0 {{ __('unit.characters') }}
                                </span>
                            </div>
                        </label>
                        <input {{ isset($disabled) ? 'disabled' : '' }} type="text" name="meta_title"
                            value="{{ old('meta_title', $model->meta_title ?? '') }}" class="form-control"
                            placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>
                                {{ __('form.seo_keyword') }}
                            </span>
                        </label>
                        <input {{ isset($disabled) ? 'disabled' : '' }} type="text" name="meta_keyword"
                            value="{{ old('meta_keyword', $model->meta_keyword ?? '') }}" class="form-control"
                            placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>
                                    {{ __('form.seo_description') }}
                                </span>
                                <span class="count_meta_description">
                                    0 {{ __('unit.characters') }}
                                </span>
                            </div>
                        </label>
                        <textarea {{ isset($disabled) ? 'disabled' : '' }} type="text" name="meta_description" class="form-control"
                            placeholder="" autocomplete="off">{{ old('meta_description', $model->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>
                                {{ __('form.canonical_url') }}
                            </span>
                            <span class="text-danger">(*)</span>
                        </label>
                        <div class="input-wrapper">
                            <input {{ isset($disabled) ? 'disabled' : '' }} type="text" name="canonical"
                                value="{{ old('canonical', $model->canonical ?? '') }}"
                                class="form-control seo_canonical" placeholder="" autocomplete="off">
                            <span class="baseUrl">{{ config('app.url') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
