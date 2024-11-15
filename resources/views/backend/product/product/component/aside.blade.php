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
                    <select name="product_catalogue_id" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('product_catalogue_id', isset($product->product_catalogue_id) ? $product->product_catalogue_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if (isset($product)) {
                foreach ($product->product_catalogues as $key => $val) {
                    if ($val->id != $product->product_catalogue_id) {
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
<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('form.general_info') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">{{ __('form.product_code') }}</label>
                    <input type="text" name="code" value="{{ old('code', $product->code ?? time()) }}"
                        class="form-control">
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">{{ __('form.product_made_in') }}</label>
                    <input type="text" name="made_in" value="{{ old('made_in', $product->made_in ?? null) }}"
                        class="form-control ">
                </div>
            </div>
        </div>
        {{-- <div class="row mb15 hidden">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">{{ __('form.product_price') }}</label>
                    <input type="text" name="price"
                        value="{{ old('price', isset($product) ? number_format($product->price, 0, ',', '.') : '') }}"
                        class="form-control int">
                </div>
            </div>
        </div> --}}
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="">{{ __('form.warranty_time') }} ({{ __('form.month') }})</label>
                    <input type="text" name="warranty_time"
                        value="{{ old('warranty_time', isset($product) ? number_format($product->warranty_time, 0, ',', '.') : '') }}"
                        class="form-control int">
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
                        <img src="{{ old('image', $product->image ?? 'backend/img/no-photo.png') }}" alt="">
                    </span>
                    <input type="hidden" name="image"
                        value="{{ old('image', $product->image ?? 'backend/img/no-photo.png') }}">
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
                                    {{ $key == old('publish', isset($product->publish) ? $product->publish : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" id="" class="form-control setupSelect2 mb15">
                        @foreach (__('follow') as $key => $val)
                            <option
                                {{ $key == old('follow', isset($product->follow) ? $product->follow : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
