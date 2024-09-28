@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['browse']['title']])
@include('backend.dashboard.component.formError')

<form action="{{ route('receipt.approve', $productReceipt->id) }}" method="post" class="box" id="yourFormId">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @include('backend.receipt.component.approve')
        </div>

        <div class="text-right mb15 button-fix">
            <a href="{{ route('receipt.monitor') }}" class="btn btn-danger">
                {{ __('button.cancel') }}
            </a>
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.browse') }}" />
        </div>
    </div>
</form>
