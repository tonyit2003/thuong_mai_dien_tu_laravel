<div class="ibox variant-box">
    <div class="ibox-title">
        <div>
            <h5>
                {{ __('form.product_variant_title') }}
            </h5>
        </div>
        <div class="description">
            {!! __('form.product_variant_description') !!}
        </div>
    </div>
    <div class="ibox-content">
        {{-- <div class="row">
            <div class="col-lg-12">
                <div class="variant-checkbox uk-flex uk-flex-middle">
                    <input type="checkbox" value="1" name="accept" id="variantCheckbox" class="variantInputCheckbox"
                        {{ old('accept') == 1 || (isset($product) && count($product->product_variants) > 0) ? 'checked' : '' }}>
                    <label for="variantCheckbox" class="turnOnVariant">{{ __('form.product_variant_lable') }}</label>
                </div>
            </div>
        </div> --}}
        {{-- <div
            class="variant-wrapper {{ old('accept') == 1 || (isset($product) && count($product->product_variants) > 0) ? '' : 'hidden' }}"> --}}
        <div class="variant-wrapper">
            <div class="row variant-container">
                <div class="col-lg-3">
                    <div class="attribute-title">
                        {{ __('form.select_attribute') }}
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="attribute-title">
                        {{ __('form.select_value_attribute') }}
                    </div>
                </div>
            </div>
            @php
                $variantCatalogue = old(
                    'attributeCatalogue',
                    isset($product->attributeCatalogue) ? json_decode($product->attributeCatalogue, true) : [],
                );
            @endphp
            <div class="variant-body">
                @if ($variantCatalogue && count($variantCatalogue))
                    @foreach ($variantCatalogue as $keyAttribute => $valAttribute)
                        <div class="row mb20 variant-item">
                            <div class="col-lg-3">
                                <div class="attribute-catalogue">
                                    <select name="attributeCatalogue[]" id=""
                                        class="choose-attribute niceSelect">
                                        <option value="0">{{ __('form.select_attribute_catalogue') }}</option>
                                        @foreach ($attributeCatalogues as $key => $val)
                                            <option {{ $val->id == $valAttribute ? 'selected' : '' }}
                                                value="{{ $val->id }}">
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                {{-- <input type="text" name="" disabled class="fake-variant form-control"> --}}

                                <select class="selectVariant variant-{{ $valAttribute }} form-control"
                                    name="attribute[{{ $valAttribute }}][]" multiple
                                    data-catid="{{ $valAttribute }}"></select>
                            </div>
                            <div class="col-lg-1">
                                <button type="button" class="remove-attribute btn btn-danger">
                                    <svg data-icon="TrashSolidLarge" aria-hidden="true" focusable="false" width="15"
                                        height="16" viewBox="0 0 15 16" class="bem-svg" style="display: block">
                                        <path fill="currentColor"
                                            d="M2 14a1 1 0 001 1h9a1 1 0 001-1V6H2v8zM13 2h-3a1 1 0 01-1-1H6a1 1 0 01-1 1H1v2h13V2h-1z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="variant-foot mt10">
                <button type="button" class="add-variant">{{ __('button.add_variant') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="ibox product-variant">
    <div class="ibox-title">
        <h5>{{ __('form.list_variant') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="table-reponsive">
            <table class="table table-striped variantTable">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<script>
    var attributeCatalogues =
        // json(): chuyển đổi dữ liệu PHP thành JSON
        @json(
            // map(): laravel => lặp qua mỗi mục trong tập hợp $attributeCatalogues và áp dụng hàm callback (function($item)) để biến đổi mỗi mục
            // map(): nhận vào 1 Collection ban đầu và trả về 1 Collection mới chứa các mục đã được biến đổi
            // $item => đại diện cho từng mục trong tập hợp.
            $attributeCatalogues->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                })->values()); // values() => loại bỏ các key gốc và tạo ra một Collection mới với các key tuần tự từ 0

    var attribute =
        "{{ base64_encode(json_encode(old('attribute', isset($product->attribute) ? json_decode($product->attribute, true) : []))) }}"; // lấy dữ liệu từ các ô input trong div hidden
    var variant =
        "{{ base64_encode(json_encode(old('variant', isset($product->variant) ? json_decode($product->variant, true) : []))) }}"

    var btnAddVariant = "{{ __('button.add_variant') }}";
    var selectAttributeCatalogue = "{{ __('form.select_attribute_catalogue') }}";
    var minimumInputLength = "{{ __('form.minimum_input_length') }}";
    var tableImageTitle = "{{ __('table.image') }}";
    var tableQuantityTitle = "{{ __('table.quantity') }}";
    var tablePriceTitle = "{{ __('table.price') }}";
    var tableSkuTitle = "{{ __('table.sku') }}";
    var updateInfoVariant = "{{ __('form.update_info_variant') }}";
    var btnCancel = "{{ __('button.cancel') }}";
    var btnSave = "{{ __('button.save') }}";
    var clickToAddImage = "{{ __('form.click_to_add_image') }}";
    var inventoryManagement = "{{ __('form.inventory_management') }}";
    var fileManagement = "{{ __('form.file_management') }}";
    var quantityTitle = "{{ __('form.quantity') }}";
    var skuTitle = "{{ __('form.sku') }}";
    var priceTitle = "{{ __('form.price') }}";
    var barcodeTitle = "{{ __('form.barcode') }}";
    var fileNameTitle = "{{ __('form.file_name') }}";
    var pathTitle = "{{ __('form.path') }}";
    var enterIdAndPrice = "{{ __('form.enter_id_and_price') }}";
</script>
