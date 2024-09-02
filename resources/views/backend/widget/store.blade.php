@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('widget.store') : route('widget.update', $widget->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.widget_infomation') }}</h5>
                    </div>
                    <div class="ibox-content widgetContent">
                        <div class="row mb30">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.short_description') }}
                                    </label>
                                    <textarea id="description" type="text" name="description" class="form-control ck-editor" placeholder=""
                                        autocomplete="off" data-height="150">{{ old('description', $widget->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('backend.dashboard.component.album')
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.configure_widget_content') }}</h5>
                    </div>
                    <div class="ibox-content model-list">
                        <div class="labelText">{{ __('form.choose_module') }}</div>
                        @foreach (__('module.model') as $key => $val)
                            <div class="model-item uk-flex uk-flex-middle">
                                <input type="radio" id="{{ $key }}" class="input-radio"
                                    value="{{ $key }}" name="model"
                                    {{ old('model', $widget->model ?? '') == $key ? 'checked' : '' }}>
                                <label for="{{ $key }}">{{ $val }}</label>
                            </div>
                        @endforeach
                        <div class="search-model-box">
                            <i class="fa fa-search"></i>
                            <input type="text" class="form-control search-model">
                            <div class="ajax-search-result">

                            </div>
                        </div>
                        @php
                            $modelItem = old('modelItem', $modelItem ?? null);
                        @endphp
                        <div class="search-model-result">
                            @if (!is_null($modelItem) && count($modelItem['id']) > 0)
                                @foreach ($modelItem['id'] as $key => $val)
                                    <div class="search-result-item" id="model-{{ $val }}"
                                        data-modelId="{{ $val }}">
                                        <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                            <div class="uk-flex uk-flex-middle">
                                                <span class="image img-cover">
                                                    <img src="{{ $modelItem['image'][$key] }}" alt="">
                                                </span>
                                                <span class="name">{{ $modelItem['name'][$key] }}</span>
                                                <div class="hidden">
                                                    <input type="text" name="modelItem[id][]"
                                                        value="{{ $val }}">
                                                    <input type="text" name="modelItem[name][]"
                                                        value="{{ $modelItem['name'][$key] }}">
                                                    <input type="text" name="modelItem[image][]"
                                                        value="{{ $modelItem['image'][$key] }}">
                                                </div>
                                            </div>
                                            <div class="deleted">
                                                <img src="backend/img/remove.png">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                @include('backend.widget.component.aside')
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
