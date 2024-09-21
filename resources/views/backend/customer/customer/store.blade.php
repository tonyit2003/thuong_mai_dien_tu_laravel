@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('customer.store') : route('customer.update', $customer->id);
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
                        <p>{{ __('form.enter_general_info', ['model' => $config['seo']['create']['model']]) }}</p>
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
                                    <input type="text" name="email"
                                        value="{{ old('email', $customer->email ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $customer->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        @php
                            $customerCatalogueSelected = isset($customer->customer_catalogue_id)
                                ? $customer->customer_catalogue_id
                                : old('customer_catalogue_id');
                            $sourceSelected = isset($customer->source_id) ? $customer->source_id : old('source_id');
                        @endphp

                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.customer_catalogue') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select name="customer_catalogue_id" id=""
                                        class="form-control setupSelect2">
                                        <option value="0">{{ __('form.select_customer_catalogue') }}</option>
                                        @foreach ($customerCatalogues as $value)
                                            <option value="{{ $value->id }}"
                                                @if ($customerCatalogueSelected == $value->id) selected @endif>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.source') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <select name="source_id" id="" class="form-control setupSelect2">
                                        <option value="0">{{ __('form.select_source') }}</option>
                                        @foreach ($sources as $value)
                                            <option value="{{ $value->id }}"
                                                @if ($sourceSelected == $value->id) selected @endif>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mt15">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.birthday') }}
                                    </label>
                                    <input type="date" name="birthday"
                                        value="{{ old('birthday', isset($customer->birthday) ? date('Y-m-d', strtotime($customer->birthday)) : '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        @if ($config['method'] == 'create')
                            <div class="row mb15">
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="" class="control-label text-left">
                                            {{ __('form.password') }}
                                            <span class="text-danger">(*)</span>
                                        </label>
                                        <input type="password" name="password" value="" class="form-control"
                                            placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-row">
                                        <label for="" class="control-label text-left">
                                            {{ __('form.re_password') }}
                                            <span class="text-danger">(*)</span>
                                        </label>
                                        <input type="password" name="re_password" value="" class="form-control"
                                            placeholder="" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="uk-flex uk-flex-space-between">
                                        <span>{{ __('form.avatar') }}</span>
                                        <span class="system-title">{{ __('form.click_to_add_avatar') }}</span>
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <span class="image img-cover img-target img-avatar">
                                                <img src="{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}"
                                                    alt="">
                                            </span>
                                            <input type="hidden" name="image"
                                                value="{{ old('image', $customer->image ?? 'backend/img/no-photo.png') }}"
                                                class="form-control input-image upload-image" data-upload="Images">
                                        </div>
                                    </div>
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
                                    <select name="province_id" class="form-control setupSelect2 province location"
                                        data-target="district">
                                        <option value="0">{{ __('form.select_province') }}</option>
                                        @if (isset($provinces))
                                            @foreach ($provinces as $province)
                                                <option @if (old('province_id') == $province->code) selected @endif
                                                    value="{{ $province->code }}">
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
                                    <select name="district_id" class="form-control setupSelect2 district location"
                                        data-target="ward">
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
                                    <input type="text" name="address"
                                        value="{{ old('address', $customer->address ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.phone') }}
                                    </label>
                                    <input type="text" name="phone"
                                        value="{{ old('phone', $customer->phone ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.note') }}
                                    </label>
                                    <input type="text" name="description"
                                        value="{{ old('description', $customer->description ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
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
    var province_id = '{{ isset($customer->province_id) ? $customer->province_id : old('province_id') }}';
    var district_id = '{{ isset($customer->district_id) ? $customer->district_id : old('district_id') }}';
    var ward_id = '{{ isset($customer->ward_id) ? $customer->ward_id : old('ward_id') }}';
</script>
