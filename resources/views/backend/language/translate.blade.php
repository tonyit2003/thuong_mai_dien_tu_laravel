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
<form action="{{ route('language.storeTranslate') }}" method="POST">
    @csrf
    <input type="hidden" name="option[id]" value="{{ $option['id'] }}">
    <input type="hidden" name="option[languageId]" value="{{ $option['languageId'] }}">
    <input type="hidden" name="option[model]" value="{{ $option['model'] }}">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                @include('backend.dashboard.component.content', [
                    'model' => $object ?? null,
                    'disabled' => 1,
                ])
                @include('backend.dashboard.component.seo', ['model' => $object ?? null, 'disabled' => 1])
            </div>
            <div class="col-lg-6">
                @include('backend.dashboard.component.translate', [
                    'model' => $objectTranslate ?? null,
                    'originalModel' => $object,
                ])
                @include('backend.dashboard.component.seoTranslate', [
                    'model' => $objectTranslate ?? null,
                    'originalModel' => $object,
                ])
            </div>
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
