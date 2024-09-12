<div id="findProduct" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">{{ __('button.close') }}</span>
                </button>
                <h4 class="modal-title">{{ __('form.choose_product') }}</h4>
                <small class="font-bold">{{ __('form.choose_product_description') }}<small>
            </div>
            <div class="modal-body">
                <div class="search-model-box">
                    <i class="fa fa-search"></i>
                    <input type="text" class="form-control search-model"
                        placeholder="{{ __('form.search_by_name_product_code') }}">
                </div>
                <div class="search-list mt20">
                    {{ __('form.loading') }}
                </div>
            </div>
            <div class="modal-footer">
                {{-- data-dismiss="modal": đóng một modal (hộp thoại) đang mở --}}
                <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('button.close') }}</button>
                <button type="submit" name="create" value="create"
                    class="btn btn-primary confirm-product-promotion">{{ __('button.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    var inventoryTitle = "{{ __('form.inventory') }}"
    var canBeSold = "{{ __('form.can_be_sold') }}"
</script>
