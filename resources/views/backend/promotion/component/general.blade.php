<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('form.general_info') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            @if (!isset($offTitle))
                <div class="col-lg-6">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            {{ __('form.promotion_name') }}
                            <span class="text-danger">(*)</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $model->name ?? '') }}"
                            class="form-control" placeholder="{{ __('form.promotion_name_input') }}" autocomplete="off">
                    </div>
                </div>
            @endif
            <div class="col-lg-6">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.promotion_code') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $model->code ?? '') }}"
                        class="form-control" placeholder="{{ __('form.promotion_code_input') }}" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.promotion_description') }}
                    </label>
                    <textarea style="height: 100px; resize: none" name="description" class="form-control form-textarea">{{ old('description', $model->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
