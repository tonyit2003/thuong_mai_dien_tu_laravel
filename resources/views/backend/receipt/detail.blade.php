@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['detail']['title']])
@include('backend.dashboard.component.formError')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        @include('backend.receipt.component.view')
    </div>

    {{-- <div class="text-right mb15 button-fix">
        <a href="{{ route('receipt.index') }}" class="btn btn-primary">{{ __('form.exit') }}</a>
    </div> --}}
</div>
