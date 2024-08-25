<form method="GET" action="{{ route('product.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @php
                        $productCatalogueId = old('product_catalogue_id') ?? request('product_catalogue_id');
                    @endphp
                    <select name="product_catalogue_id" class="form-control mr10 setupSelect2" id="">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $key == $productCatalogueId ? 'selected' : '' }} value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('product.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('product.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
