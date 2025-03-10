<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">{{ __('form.general_info_inventory') }}</div>
        <div class="ibox-content">

            <div class="filter-wrapper">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <div class="uk-flex uk-flex-middle" style="margin-left: auto;" id="keywordInput">
                        <select name="product_id" class="form-control mr10 setupSelect2" id="productSelect" style="max-width: none;">
                            <option value="">{{ __('form.select_product') }}</option>
                            <!-- Product options will be populated here via AJAX -->
                        </select>
                        <input value="{{ request('num') ?: old('num') }}" type="number" name="num" id="quantityInput"
                            placeholder="{{ __('form.enter_inventory_quantity') }}" class="form-control mr10" style="width: 155px; border-radius: 0;">

                        <input value="{{ request('keyword') ?: old('keyword') }}" type="text" name="keyword" id=""
                            placeholder="{{ __('form.enter_keyword') }}" class="form-control" style="width: 200px; border-radius: 0">
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 5%">
                            <input type="checkbox" value="" id="checkAllReceipt" class="input-checkbox" />
                        </th>
                        <th class="text-center" style="width: 45%">{{ __('table.product_name') }}</th>
                        <th class="text-center" style="width: 40%">{{ __('table.version') }}</th>
                        <th class="text-center" style="width: 10%">{{ __('table.inventory_quantity') }}</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <!-- Các dòng sẽ được thêm vào đây -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-6">
    <div class="ibox">
        <div class="ibox-title">{{ __('form.warehouse_receipt') }}</div>
        <div class="ibox-content">
            <div class="filter-wrapper">
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <div class="uk-flex uk-flex-middle" style="margin-left: auto;" id="keywordInput">
                        <label for="productSelect" class="mr10">{{ __('table.supplier') }} <span class="text-danger">(*)</span></label>
                        @if ($config['method'] == 'edit')
                            <select name="supplier_id" class="form-control mr10 setupSelect2" disabled id="supplierField">
                                @foreach ($suppliers as $key => $val)
                                    <option value="{{ $val->id }}" {{ $val->id == $productReceipt->supplier_id ? 'selected' : '' }}>
                                        {{ $val->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="supplier_id" value="{{ $productReceipt->supplier_id }}">
                        @else
                            <select name="supplier_id" class="form-control mr10 setupSelect2" id="supplierField">
                                @foreach ($suppliers as $key => $val)
                                    <option value="{{ $val->id }}" {{ old('supplier_id') == $val->id ? 'selected' : '' }}>
                                        {{ $val->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="supplier_id" id="hiddenSupplierId" value="">
                        @endif
                    </div>
                </div>
            </div>

            <table class="table table-striped table-bordered" id="productTable">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 25%">{{ __('table.product_name') }}</th>
                        <th class="text-center" style="width: 43%">{{ __('table.version') }}</th>
                        <th class="text-center" style="width: 10%">{{ __('table.quantity_imported') }}</th>
                        <th class="text-center" style="width: 17%">{{ __('table.price') }}</th>
                        <th class="text-center" style="width: 5%">{{ __('table.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="productTableBodyRereipt">
                    <!-- Các dòng sẽ được thêm vào đây -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    var no_product = "{{ __('table.no_product') }}";
    var price = "{{ __('form.enter_price') }}";
    var productReceiptId = "{{ $productReceipt->id ?? 0 }}";
    var productname = "{{ __('form.select_product') }}";
</script>
