@include(
    'backend.dashboard.component.breadcrumb',
    $config['method'] == 'create'
        ? ['title' => $config['seo']['create']['title']]
        : ['title' => $config['seo']['edit']['title']]
)

@include('backend.dashboard.component.formError')

@php
    $url = $config['method'] == 'create' ? route('promotion.store') : route('promotion.update', $promotion->id);
@endphp

<form action="{{ $url }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight promotion-wrapper">
        <div class="row">
            <div class="col-lg-8">
                @include('backend.promotion.component.general', ['model' => $promotion ?? null])
                @include('backend.promotion.promotion.component.detail')
            </div>
            @include('backend.promotion.component.aside', ['model' => $promotion ?? null])
        </div>
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>
@include('backend.promotion.promotion.component.popup')
<input type="hidden" class="input-product-and-quantity" value="{{ json_encode(__('module.item')) }}">
<input type="hidden" name="" class="preload_promotionMethod"
    value="{{ old('method', $promotion->method ?? null) }}">
<input type="hidden" name="" class="preload_select-product-and-quantity"
    value="{{ old('module_type', $promotion->discountInformation['info']['model'] ?? null) }}">
<input type="hidden" name="" class="input_order_amount_range"
    value="{{ json_encode(old('promotion_order_amount_range', $promotion->discountInformation['info'] ?? [])) }}">
<input type="hidden" name="" class="input_product_and_quantity"
    value="{{ json_encode(old('product_and_quantity', $promotion->discountInformation['info'] ?? [])) }}">
<input type="hidden" name="" class="input_object"
    value="{{ json_encode(old('object', $promotion->discountInformation['info']['object'] ?? [])) }}">

<script>
    var clickToSelect = "{{ __('form.click_to_select') }}"
    var cash = "{{ __('form.cash') }}"
    var enterValueTo = "{{ __('form.enter_value_to') }}"
    var valueToGreaterThanValueFrom = "{{ __('form.value_to_greater_than_value_from') }}"
    var conflictRange = "{{ __('form.conflict_range') }}"
    var valueFrom = "{{ __('form.value_from') }}"
    var valueTo = "{{ __('form.value_to') }}"
    var discount = "{{ __('form.discount') }}"
    var addCondition = "{{ __('form.add_condition') }}"
    var minimumInputLength = "{{ __('form.minimum_input_length') }}"
    var purchasedProduct = "{{ __('form.purchased_product') }}"
    var minimumQuantity = "{{ __('form.minimum_quantity') }}"
    var promotionalLimit = "{{ __('form.promotional_limit') }}"
    var applicableProduct = "{{ __('form.applicable_product') }}"
    var searchByNameProductCode = "{{ __('form.search_by_name_product_code') }}"
</script>
