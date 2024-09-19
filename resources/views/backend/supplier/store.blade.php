@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create' ? ['title' => $config['seo']['create']['title']] : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('supplier.store') : route('supplier.update', $supplier->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('form.general_info') }}
                    </div>
                    <div class="panel-description">
                        <p>{{ __('form.enter_general_info', ['model' => 'thành viên']) }}</p>
                        <p>{!! __('form.required_fields') !!}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.email') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.product_catalogue') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select multiple name="catalogue[]" id="" class="form-control setupSelect2">
                                        @foreach ($productCatalogues as $val)
                                            @if ($val->product_catalogue_language->isNotEmpty())
                                                @foreach ($val->product_catalogue_language as $language)
                                                    <option
                                                        @isset($selectedCatalogues) @if (in_array($val->id, old('catalogue', $selectedCatalogues))) selected @endif @endisset
                                                        value="{{ $val->id }}">
                                                        {{ $language->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.fax') }}
                                    </label>
                                    <input type="text" name="fax" value="{{ old('fax', $supplier->fax ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        {{ __('form.contact_info') }}
                    </div>
                    <div class="panel-description">
                        <p>{{ __('form.enter_contact_info') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.province') }}
                                    </label>
                                    <select name="province_id" class="form-control setupSelect2 province location" data-target="district">
                                        <option value="0">{{ __('form.select_province') }}</option>
                                        @if (isset($provinces))
                                            @foreach ($provinces as $province)
                                                <option @if (old('province_id') == $province->code) selected @endif value="{{ $province->code }}">
                                                    {{ $province->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.district') }}
                                    </label>
                                    <select name="district_id" class="form-control setupSelect2 district location" data-target="ward">
                                        <option value="0">{{ __('form.select_district') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.ward') }}
                                    </label>
                                    <select name="ward_id" class="form-control setupSelect2 ward">
                                        <option value="0">{{ __('form.select_ward') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.address') }}
                                    </label>
                                    <input type="text" name="address" value="{{ old('address', $supplier->address ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.phone') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>

<script>
    var province_id = '{{ isset($supplier->province_id) ? $supplier->province_id : old('province_id') }}';
    var district_id = '{{ isset($supplier->district_id) ? $supplier->district_id : old('district_id') }}';
    var ward_id = '{{ isset($supplier->ward_id) ? $supplier->ward_id : old('ward_id') }}';
</script>
