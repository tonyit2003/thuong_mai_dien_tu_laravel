<form method="GET" action="{{ route('receipt.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterReceipt')
                    @php
                        $productCatalogueId = old('product_catalogue_id') ?? request('product_catalogue_id');
                    @endphp

                    @include('backend.dashboard.component.keyword')

                    <a href="{{route('receipt.create') }}" class="btn btn-danger mr10">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('receipt.instock.title') }}
                    </a>

                    <a href="{{ route('receipt.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('receipt.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
