<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('form.promotion_detail_setting') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="form-row">
            <div class="fix-label">{{ __('form.select_promotion_type') }}</div>
            <select name="method" class="setupSelect2 promotionMethod" id="">
                <option value="none">{{ __('form.select_type') }}</option>
                @foreach (__('module.promotion') as $key => $val)
                    <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>
        <div class="promotion-container">

        </div>
    </div>
</div>
