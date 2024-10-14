@if (isset($attributeCatalogues))
    @foreach ($attributeCatalogues as $attributeCatalogue)
        <div class="attribute">
            <div class="attribute-item attribute-color">
                <div class="label">{{ $attributeCatalogue->name }}: <span> </span></div>
                @if (isset($attributeCatalogue->attribute))
                    <div class="attribute-value">
                        @foreach ($attributeCatalogue->attribute as $attribute)
                            <a class="choose-attribute {{ in_array($attribute->id, explode(',', $productVariant->code)) ? 'active' : '' }}"
                                data-attributeid = "{{ $attribute->id }}"
                                title="{{ $attribute->name }}">{{ $attribute->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@endif

<input type="hidden" name="product_id" value="{{ $product->id }}">
<input type="hidden" name="language_id" value="{{ $language }}">
<input type="hidden" name="product_canonical" value="{{ $product->canonical }}">
