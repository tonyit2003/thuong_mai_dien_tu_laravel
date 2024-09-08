<div id="findProduct" class="modal fade">
    <form action="" class="form create-menu-catalogue" method="">
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
                        @for ($i = 0; $i < 10; $i++)
                            <div class="search-object-item">
                                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                    <div class="object-info">
                                        <div class="uk-flex uk-flex-middle">
                                            <input type="checkbox" name="" value="" class="input-checkbox">
                                            <span class="image img-scaledown">
                                                <img src="http://localhost:8081/thuongmaidientu/public/userfiles/image/thoi-su/cam-nhan-nhanh-samsung-galaxy-z-fold6-flip6-12.jpg"
                                                    alt="">
                                            </span>
                                            <div class="object-name">
                                                <div class="name">
                                                    Sạc Iphone
                                                </div>
                                                <div class="jscode">123</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="object-extra-info">
                                        <div class="price">1.200.000</div>
                                        <div class="object-inventory">
                                            <div class="uk-flex uk-flex-middle">
                                                <span class="text-1">{{ __('form.inventory') }}:</span>
                                                <span class="text-value"> 10.000</span>
                                                <span class="text-1 slash">|</span>
                                                <span class="text-1">{{ __('form.can_be_sold') }}:</span>
                                                <span class="text-value"> 9000</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- data-dismiss="modal": đóng một modal (hộp thoại) đang mở --}}
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('button.close') }}</button>
                    <button type="submit" name="create" value="create"
                        class="btn btn-primary">{{ __('button.confirm') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
