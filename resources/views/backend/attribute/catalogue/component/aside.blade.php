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
                    <span class="text-danger notice">
                        *{{ __('form.select_root_if_no_parent') }}
                    </span>
                    <select name="parent_id" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('parent_id', isset($attributeCatalogue->parent_id) ? $attributeCatalogue->parent_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
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
                        <img src="{{ old('image', $attributeCatalogue->image ?? 'backend/img/no-photo.png') }}"
                            alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', $attributeCatalogue->image ?? 'backend/img/no-photo.png') }}">
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
                                    {{ $key == old('publish', isset($attributeCatalogue->publish) ? $attributeCatalogue->publish : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" id="" class="form-control setupSelect2 mb15">
                        @foreach (__('follow') as $key => $val)
                            <option
                                {{ $key == old('follow', isset($attributeCatalogue->follow) ? $attributeCatalogue->follow : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
