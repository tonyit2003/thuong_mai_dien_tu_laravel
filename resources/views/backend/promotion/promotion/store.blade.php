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
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.general_info') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.promotion_name') }}
                                        <span class="text-danger">(*)</span>
                                    </label>
                                    <input type="text" name="name"
                                        value="{{ old('name', $promotion->name ?? '') }}" class="form-control"
                                        placeholder="{{ __('form.promotion_name_input') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.promotion_code') }}
                                    </label>
                                    <input type="text" name="code"
                                        value="{{ old('code', $promotion->code ?? '') }}" class="form-control"
                                        placeholder="{{ __('form.promotion_code_input') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        {{ __('form.promotion_description') }}
                                    </label>
                                    <textarea style="height: 100px; resize: none" name="description" class="form-control form-textarea">{{ old('description', $promotion->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.promotion_detail_setting') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row">
                            <div class="fix-label">{{ __('form.select_promotion_type') }}</div>
                            <select name="" class="setupSelect2 promotionMethod" id="">
                                <option value="">{{ __('form.select_type') }}</option>
                                @foreach (__('module.promotion') as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="promotion-container">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.promotion_period') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="form-row mb15">
                            <label for="" class="control-label text-left">{{ __('form.start_date') }}</label>
                            <div class="form-date">
                                <input type="text" name="startDate"
                                    value="{{ old('startDate', $promotion->startDate ?? '') }}"
                                    class="form-control datepicker" placeholder="" autocomplete="off">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-row mb15">
                            <label for="" class="control-label text-left">{{ __('form.end_date') }}</label>
                            <div class="form-date">
                                <input type="text" name="endDate"
                                    value="{{ old('endDate', $promotion->endDate ?? '') }}"
                                    class="form-control datepicker" placeholder="" autocomplete="off">
                                <span><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="uk-flex uk-flex-middle">
                                <input type="checkbox" name="neverEnd" value="accept" class="" id="neverEnd">
                                <label for="neverEnd" class="fix-label ml5">{{ __('form.never_end') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.applicable_customer_source') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="setting-value">
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input type="radio" name="source" value="all" id="allSource" class="chooseSource"
                                    checked>
                                <label for="allSource"
                                    class="fix-label ml5">{{ __('form.applicable_to_all_customer_source') }}</label>
                            </div>
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input type="radio" name="source" value="choose" id="chooseSource"
                                    class="chooseSource">
                                <label for="chooseSource"
                                    class="fix-label ml5">{{ __('form.select_applicable_customer_source') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('form.applicable_customer') }}</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="setting-value">
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input type="radio" name="apply" value="all" id="allApply"
                                    class="chooseApply" checked>
                                <label for="allApply"
                                    class="fix-label ml5">{{ __('form.applicable_to_all_customer') }}</label>
                            </div>
                            <div class="nav-setting-item uk-flex uk-flex-middle">
                                <input type="radio" name="apply" value="choose" id="chooseApply"
                                    class="chooseApply">
                                <label for="chooseApply"
                                    class="fix-label ml5">{{ __('form.select_applicable_customer') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb15">
            <input class="btn btn-primary" type="submit" name="send" value="{{ __('button.save') }}" />
        </div>
    </div>
</form>

@include('backend.promotion.promotion.component.popup')
<input type="hidden" class="input-product-and-quantity" value="{{ json_encode(__('module.item')) }}">

<script>
    var clickToSelect = "{{ __('form.click_to_select') }}"
    var staffTakeCareCustomer = "{{ __('form.staff_take_care_customer') }}"
    var customerGroup = "{{ __('form.customer_group') }}"
    var customerGender = "{{ __('form.gender') }}"
    var customerBirthday = "{{ __('form.birthday') }}"
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
