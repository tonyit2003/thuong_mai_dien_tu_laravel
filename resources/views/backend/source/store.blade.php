@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('source.store') : route('source.update', $source->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.source_infomation') }}</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        <div class="row mb30">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.short_description') }}
                                    </label>
                                    <textarea id="description" type="text" name="description" class="form-control ck-editor" placeholder=""
                                        autocomplete="off" data-height="150">{{ old('description', $source->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('backend.source.component.aside')
            </div>
        </div>
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>

<script>
    var chooseModuleMessage = "{{ __('form.choose_module_message') }}";
</script>
