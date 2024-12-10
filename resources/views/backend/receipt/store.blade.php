@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create' ? ['title' => $config['seo']['create']['title']] : ['title' => $config['seo']['edit']['title']]
)
@include('backend.dashboard.component.formError')
@php
    $url = $config['method'] == 'create' ? route('receipt.store') : route('receipt.update', $productReceipt->id);
@endphp
<form action="{{ $url }}" method="post" class="box" id="yourFormId">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @include('backend.receipt.component.content')
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>

<script>
    var lang = {{ $lang }};
</script>
