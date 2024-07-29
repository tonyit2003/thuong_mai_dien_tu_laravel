@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)
@include('backend.dashboard.component.formError')
@php
    $url =
        $config['method'] == 'create'
            ? route('product.catalogue.store')
            : route('product.catalogue.update', $productCatalogue->id);
@endphp
<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                @include('backend.dashboard.component.content', ['model' => $productCatalogue ?? null])
                @include('backend.dashboard.component.album')
                @include('backend.dashboard.component.seo', ['model' => $productCatalogue ?? null])
            </div>
            <div class="col-lg-3">
                @include('backend.product.catalogue.component.aside')
            </div>
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
