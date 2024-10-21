<div class="panel-head">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <h2 class="cart-heading">
            <span>{{ __('info.order_information') }}</span>
        </h2>
        {{-- <span class="has-account">{{ __('info.has_account') }}
            <a href="{{ route('authClient.index') }}" title="{{ __('info.login_now') }}">
                {{ __('info.login_now') }}
            </a>
        </span> --}}
    </div>
</div>
<div class="panel-body mb30">
    <div class="cart-infomation">
        <div class="uk-grid uk-grid-medium mb20">
            <div class="uk-width-large-1-2">
                <div class="form-row">
                    <input type="text" name="fullname" id=""
                        value="{{ isset($model->name) ? $model->name : '' }}" placeholder="{{ __('form.enter_name') }}"
                        class="input-text">
                </div>
            </div>
            <div class="uk-width-large-1-2">
                <div class="form-row">
                    <input type="text" name="phone" id=""
                        value="{{ isset($model->phone) ? $model->phone : '' }}"
                        placeholder="{{ __('form.enter_phone') }}" class="input-text">
                </div>
            </div>
        </div>
        <div class="form-row mb20">
            <input type="text" name="email" id="" value="{{ isset($model->email) ? $model->email : '' }}"
                placeholder="{{ __('form.enter_email') }}" class="input-text">
        </div>
        <div class="uk-grid uk-grid-medium mb20">
            <div class="uk-width-large-1-3">
                <select name="province_id" id="" class="setupSelect2 province location" data-target="district">
                    <option value="0">{{ __('form.select_province') }}</option>
                    @foreach ($provinces as $key => $val)
                        <option @if (old('province_id') == $val->code) selected @endif value="{{ $val->code }}">
                            {{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="uk-width-large-1-3">
                <select name="district_id" id="" class="setupSelect2 district location" data-target="ward">
                    <option value="0">{{ __('form.select_district') }}</option>
                </select>
            </div>
            <div class="uk-width-large-1-3">
                <select name="ward_id" id="" class="setupSelect2 ward">
                    <option value="0">{{ __('form.select_ward') }}</option>
                </select>
            </div>
        </div>
        <div class="form-row mb20">
            <input type="text" name="address" id=""
                value="{{ isset($model->address) ? $model->address : '' }}"
                placeholder="{{ __('form.enter_address') }}" class="input-text">
        </div>
        <div class="form-row">
            <input type="text" name="description" id="" value="" placeholder="{{ __('form.note') }}"
                class="input-text">
        </div>
    </div>
</div>

<script>
    var province_id = '{{ isset($model->province_id) ? $model->province_id : old('province_id') }}';
    var district_id = '{{ isset($model->district_id) ? $model->district_id : old('district_id') }}';
    var ward_id = '{{ isset($model->ward_id) ? $model->ward_id : old('ward_id') }}';
</script>
