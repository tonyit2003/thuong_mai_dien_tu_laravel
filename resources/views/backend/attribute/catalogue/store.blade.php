@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create' ? ['title' => $config['seo']['create']['title']] : ['title' => $config['seo']['edit']['title']]
)
@include('backend.dashboard.component.formError')
@php
    $url = $config['method'] == 'create' ? route('attribute.catalogue.store') : route('attribute.catalogue.update', $attributeCatalogue->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>
                            {{ __('form.general_info') }}
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.attribute_catalogue_name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name', $attributeCatalogue->name ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off" {{ isset($disabled) ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>
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
                        <div class="text-right mb15 button-fix">
                            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
        </div>
    </div>
</form>
