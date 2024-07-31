@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)
@include('backend.dashboard.component.formError')
@php
    $url = $config['method'] == 'create' ? route('attribute.store') : route('attribute.update', $attribute->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                @include('backend.dashboard.component.content', ['model' => $attribute ?? null])
                @include('backend.dashboard.component.album')
                @include('backend.dashboard.component.seo', ['model' => $attribute ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.attribute.attribute.component.aside')
            </div>
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
