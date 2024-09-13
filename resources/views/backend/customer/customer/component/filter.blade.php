<form method="GET" action="{{ route('customer.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @include('backend.dashboard.component.filterPublish')
                    @php
                        $customerCatalogueSelected = old('customer_catalogue_id') ?? request('customer_catalogue_id');
                    @endphp
                    <select name="customer_catalogue_id" class="form-control mr10 setupSelect2" id="">
                        <option value="0">
                            {{ __('form.select_customer_catalogue') }}
                        </option>
                        @foreach ($customerCatalogues as $value)
                            <option value="{{ $value->id }}" @if ($customerCatalogueSelected == $value->id) selected @endif>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @php
                        $sourceSelected = old('source_id') ?? request('source_id');
                    @endphp
                    <select name="source_id" class="form-control mr10 setupSelect2" id="">
                        <option value="0">
                            {{ __('form.select_source') }}
                        </option>
                        @foreach ($sources as $value)
                            <option value="{{ $value->id }}" @if ($sourceSelected == $value->id) selected @endif>
                                {{ $value->name }}</option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.keyword')
                    <a href="{{ route('customer.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('customer.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
