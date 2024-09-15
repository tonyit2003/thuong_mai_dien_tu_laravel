@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['instock']['title']])
@include('backend.dashboard.component.formError')

<form action="{{ route('receipt.delivere', $productReceipt->id) }}" method="post" class="box" id="">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @include('backend.receipt.component.delivere')
        </div>

        <div class="text-right mb15 button-fix">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
