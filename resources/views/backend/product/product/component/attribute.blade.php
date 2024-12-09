<div class="ibox variant-box">
    <div class="ibox-title">
        <div>
            <h5>
                {{ __('form.product_attributes') }}
            </h5>
        </div>
        <div class="description">
            {!! __('form.product_attributes_description') !!}
        </div>
    </div>
    <div class="ibox-content">
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
                $generalAttributeCatalogue = old(
                    'generalAttributeCatalogue',
                    isset($product->attributes) ? $product->attributes->pluck('attribute_catalogue_id') : [],
                );
            @endphp
            <div class="general-attribute-body">
                @if ($generalAttributeCatalogue && count($generalAttributeCatalogue))
                    @foreach ($generalAttributeCatalogue as $generalAttribute)
                        <div class="row mb20 general-attribute-item uk-flex uk-flex-middle">
                            <div class="col-lg-3">
                                <div class="general-attribute-catalogue">
                                    <select name="generalAttributeCatalogue[]" id=""
                                        class="choose-general-attribute niceSelect">
                                        <option value="0">{{ __('form.select_attribute_catalogue') }}</option>
                                        @foreach ($attributeCatalogues as $key => $val)
                                            <option {{ $val->id == $generalAttribute ? 'selected' : '' }}
                                                value="{{ $val->id }}">
                                                {{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <select
                                    class="selectGeneralAttribute general-attribute-{{ $generalAttribute }} form-control"
                                    name="generalAttribute[{{ $generalAttribute }}]"
                                    data-catid="{{ $generalAttribute }}"></select>
                            </div>
                            <div class="col-lg-1">
                                <button type="button" class="remove-general-attribute btn btn-danger">
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
            <div class="general-attribute-foot mt10">
                <button type="button" class="add-general-attribute">{{ __('button.add_attribute') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    var attributeCatalogues =
        @json(
            $attributeCatalogues->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                    ];
                })->values());

    var generalAttribute =
        "{{ base64_encode(json_encode(old('generalAttribute', isset($product->attributes) ? $product->attributes->pluck('id', 'attribute_catalogue_id')->toArray() : []))) }}";

    var btnAddAttribute = "{{ __('button.add_attribute') }}";
    var selectAttributeCatalogue = "{{ __('form.select_attribute_catalogue') }}";
    var minimumInputLength = "{{ __('form.minimum_input_length') }}";
</script>
