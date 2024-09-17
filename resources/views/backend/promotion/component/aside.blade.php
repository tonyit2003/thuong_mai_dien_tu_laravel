<div class="col-lg-4">
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ __('form.promotion_period') }}</h5>
        </div>
        <div class="ibox-content">
            <div class="form-row mb15">
                <label for="" class="control-label text-left">
                    {{ __('form.start_date') }}
                    <span class="text-danger">(*)</span></label>
                <div class="form-date">
                    <input type="text" name="startDate"
                        value="{{ old('startDate', isset($model->startDate) ? convertDateTime($model->startDate) : '') }}"
                        class="form-control datepicker" placeholder="" autocomplete="off">
                    <span><i class="fa
                        fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-row mb15">
                <label for="" class="control-label text-left">
                    {{ __('form.end_date') }}
                    <span class="text-danger">(*)</span>
                </label>
                <div class="form-date">
                    <input type="text" name="endDate"
                        value="{{ old('endDate', isset($model->endDate) ? convertDateTime($model->endDate) : '') }}"
                        class="form-control datepicker" placeholder="" autocomplete="off"
                        @if (old('neverEndDate', $model->neverEndDate ?? null) == 'accept') disabled @endif>
                    <span><i class="fa fa-calendar"></i></span>
                </div>
            </div>
            <div class="form-row">
                <div class="uk-flex uk-flex-middle">
                    <input type="checkbox" name="neverEndDate" value="accept" class="" id="neverEnd"
                        @if (old('neverEndDate', $model->neverEndDate ?? null) == 'accept') checked="checked" @endif>
                    <label for="neverEnd" class="fix-label ml5">{{ __('form.never_end') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ __('form.applicable_customer_source') }}</h5>
        </div>
        <div class="ibox-content">
            @php
                $sourceStatus = old('sourceStatus', $model->discountInformation['source']['status'] ?? null);
                $sourceValue = old('sourceValue', $model->discountInformation['source']['data'] ?? []);
            @endphp
            <div class="setting-value">
                <div class="nav-setting-item uk-flex uk-flex-middle">
                    <input type="radio" name="sourceStatus" value="all" id="allSource" class="chooseSource"
                        {{ !old('source') || old('sourceStatus', $model->discountInformation['source']['status'] ?? '') === 'all' ? 'checked' : '' }}>
                    <label for="allSource"
                        class="fix-label ml5">{{ __('form.applicable_to_all_customer_source') }}</label>
                </div>
                <div class="nav-setting-item uk-flex uk-flex-middle">
                    <input type="radio" name="sourceStatus" value="choose" id="chooseSource" class="chooseSource"
                        {{ old('sourceStatus', $model->discountInformation['source']['status'] ?? '') === 'choose' ? 'checked' : '' }}>
                    <label for="chooseSource"
                        class="fix-label ml5">{{ __('form.select_applicable_customer_source') }}</label>
                </div>
            </div>
            @if (isset($sourceStatus))
                <div class="source-wrapper">
                    <select name="sourceValue[]" id="" class="multipleSelect2" multiple>
                        @foreach ($sources as $key => $val)
                            <option value="{{ $val->id }}"
                                {{ in_array($val->id, $sourceValue) ? 'selected' : '' }}>
                                {{ $val->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>{{ __('form.applicable_customer') }}</h5>
        </div>
        <div class="ibox-content">
            @php
                $applyStatus = old('applyStatus', $model->discountInformation['apply']['status'] ?? null);
                $applyValue = old('applyValue', $model->discountInformation['apply']['data'] ?? []);
            @endphp
            <div class="setting-value">
                <div class="nav-setting-item uk-flex uk-flex-middle">
                    <input type="radio" name="applyStatus" value="all" id="allApply" class="chooseApply"
                        {{ !old('applyStatus') || old('applyStatus', $model->discountInformation['apply']['status'] ?? '') === 'all' ? 'checked' : '' }}>
                    <label for="allApply" class="fix-label ml5">{{ __('form.applicable_to_all_customer') }}</label>
                </div>
                <div class="nav-setting-item uk-flex uk-flex-middle">
                    <input type="radio" name="applyStatus" value="choose" id="chooseApply" class="chooseApply"
                        {{ old('applyStatus', $model->discountInformation['apply']['status'] ?? '') === 'choose' ? 'checked' : '' }}>
                    <label for="chooseApply" class="fix-label ml5">{{ __('form.select_applicable_customer') }}</label>
                </div>
            </div>
            @if (isset($applyStatus))
                <div class="apply-wrapper">
                    <select name="applyValue[]" id="" class="multipleSelect2 conditionItem" multiple>
                        @foreach (__('module.applyStatus') as $key => $val)
                            <option value="{{ $val['id'] }}">{{ $val['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="wrapper-condition">

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<input type="hidden" name="" id="" class="applyStatusList"
    value="{{ json_encode(__('module.applyStatus')) }}">
<input type="hidden" name="" class="conditionItemSelected" value="{{ json_encode($applyValue) }}">
@if (count($applyValue))
    @foreach ($applyValue as $key => $val)
        <input type="hidden" class="condition_input_{{ $val }}"
            value="{{ json_encode(old($val, $model->discountInformation['apply']['condition'][$val] ?? [])) }}">
    @endforeach
@endif
