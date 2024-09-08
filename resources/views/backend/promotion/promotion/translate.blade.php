@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['translate']['title']])
@if ($errors->any())
    <div class="alert alert-danger mt20">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('widget.saveTranslate') }}" method="POST">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <input type="hidden" name="translateId" id="" value="{{ $translate->id }}">
        <input type="hidden" name="widgetId" id="" value="{{ $widget->id }}">
        <div class="row">
            <div class="col-lg-6">
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
                                        autocomplete="off" data-height="150" disabled>{{ old('description', $widget->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
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
                                    <textarea id="description_1" type="text" name="translate_description" class="form-control ck-editor" placeholder=""
                                        autocomplete="off" data-height="150">{{ old('translate_description', $widget->translateDescription ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
