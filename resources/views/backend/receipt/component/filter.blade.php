<form method="GET" action="{{ route('receipt.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            @include('backend.dashboard.component.perPage')
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @php
                        $supplier = request('supplier') != null ? request('supplier') : -1;
                        $user = request('user') != null ? request('user') : -1;
                        $time =
                            request('date_approved') != null ? request('date_approved') : now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i');
                    @endphp
                    <label for="" class="control-label text-left mr5">{{ __('form.date_approved') }}</label>
                    <div class="form-date mr10">
                        <input type="text" name="date_approved" value="{{ old('date_approved', $time ?? '') }}"
                            class="form-control datepickerSearchTime" placeholder="" autocomplete="off">
                        <span><i class="fa fa-calendar"></i></span>
                    </div>
                    <select name="supplier" class="form-control mr10 setupSelect2">
                        <option {{ $supplier == 0 ? 'selected' : '' }} value="0">{{ __('form.choose_supplier') }}</option>
                        @foreach ($suppliers as $key => $val)
                            <option {{ $val->id == $supplier ? 'selected' : '' }} value="{{ $val->id }}">
                                {{ $val->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="user" class="form-control mr10 setupSelect2">
                        <option {{ $user == 0 ? 'selected' : '' }} value="0">{{ __('form.choose_recipt') }}</option>
                        @foreach ($users as $key => $val)
                            <option {{ $val->id == $user ? 'selected' : '' }} value="{{ $val->id }}">
                                {{ $val->name }}
                            </option>
                        @endforeach
                    </select>
                    @include('backend.dashboard.component.filterReceipt')
                    @php
                        $productCatalogueId = old('product_catalogue_id') ?? request('product_catalogue_id');
                    @endphp

                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary mb0 btn-sm">
                                    {{ __('button.search') }}
                                </button>
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('receipt.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('receipt.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
