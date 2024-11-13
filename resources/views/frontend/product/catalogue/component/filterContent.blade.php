<div class="filter-content uk-hidden">
    <div class="filter-overlay">
        <div class="filter-close">
            <i class="fi fi-rs-cross"></i>
        </div>
        <div class="filter-content-container">
            @if (isset($filters))
                @foreach ($filters as $key => $val)
                    @php
                        $catName = $val->languages->first()->pivot->name;
                        if (!isset($val->attributes) || count($val->attributes) == 0) {
                            continue;
                        }
                    @endphp
                    <div class="filter-item">
                        <div class="filter-heading">{{ $catName }}</div>
                        @if (isset($val->attributes) && count($val->attributes))
                            <div class="filter-body">
                                @foreach ($val->attributes as $item)
                                    @php
                                        $attributeName = $item->languages->first()->pivot->name;
                                        $id = $item->id;
                                    @endphp
                                    <div class="filter-choose uk-flex uk-flex-middle">
                                        <input type="checkbox" id="attribute-{{ $id }}"
                                            class="input-checkbox filtering filterAttribute" value="{{ $id }}"
                                            data-group="{{ $val->id }}">
                                        <label for="attribute-{{ $id }}">{{ $attributeName }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
            <div class="filter-item filter-price slider-box">
                <div class="filter-heading" for="priceRange">{{ __('info.filter_by_price') }}:</div>
                <div class="filter-price-content">
                    <input type="text" id="priceRange" readonly class="uk-hidden">
                    <div id="price-range" class="slider ui-slider ui-slider-horizontal ui-widget ui-widget-content">
                    </div>
                </div>
                <div class="filter-input-value mt5">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <input type="text" class="min-value input-value">
                        <input type="text" class="max-value input-value">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="" value="{{ $productCatalogue->id }}" class="product_catalogue_id">
