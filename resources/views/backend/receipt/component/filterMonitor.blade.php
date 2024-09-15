<form method="GET" action="{{ route('receipt.monitor') }}">
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
                </div>
            </div>
        </div>
    </div>
</form>
