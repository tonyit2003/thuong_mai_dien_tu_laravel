<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('form.select_parent_category') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.select_parent_category') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <select name="attribute_catalogue_id" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('attribute_catalogue_id', isset($attribute->attribute_catalogue_id) ? $attribute->attribute_catalogue_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if (isset($attribute)) {
                foreach ($attribute->attribute_catalogues as $key => $val) {
                    if ($val->id != $attribute->attribute_catalogue_id) {
                        $catalogue[] = $val->id;
                    }
                }
            }
        @endphp
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.select_sub_category') }}
                    </label>
                    <select multiple name="catalogue[]" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option @if (is_array(old('catalogue', isset($catalogue) && count($catalogue) ? $catalogue : [])) &&
                                    in_array($key, old('catalogue', isset($catalogue) && count($catalogue) ? $catalogue : []))) selected @endif value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('form.select_thumbnail') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover img-target">
                        <img src="{{ old('image', $attribute->image ?? 'backend/img/no-photo.png') }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', $attribute->image ?? 'backend/img/no-photo.png') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('form.advanced_settings') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" id="" class="form-control setupSelect2 mb15">
                            @foreach (__('publish') as $key => $val)
                                <option
                                    {{ $key == old('publish', isset($attribute->publish) ? $attribute->publish : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" id="" class="form-control setupSelect2 mb15">
                        @foreach (__('follow') as $key => $val)
                            <option
                                {{ $key == old('follow', isset($attribute->follow) ? $attribute->follow : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
