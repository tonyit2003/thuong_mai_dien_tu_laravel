<form method="GET" action="{{ route('product.catalogue.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('product.catalogue.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('productCatalogue.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
