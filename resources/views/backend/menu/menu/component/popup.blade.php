<div id="createMenuCatalogue" class="modal fade">
    <form action="" class="form create-menu-catalogue" method="">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">{{ __('button.close') }}</span>
                    </button>
                    <h4 class="modal-title">{{ __('form.menu_location_create') }}</h4>
                    <small class="font-bold">{{ __('form.menu_location_create_description') }}<small>
                </div>
                <div class="modal-body">
                    <div class="form-error hidden"></div>
                    <div class="row">
                        <div class="col-lg-12 mb10">
                            <label for="">{{ __('form.menu_location_name') }}</label>
                            <input type="text" class="form-control" value="" name="name">
                            <div class="error name"></div>
                        </div>
                        <div class="col-lg-12 mb10">
                            <label for="">{{ __('form.keyword') }}</label>
                            <input type="text" class="form-control" value="" name="keyword">
                            <div class="error keyword"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- data-dismiss="modal": đóng một modal (hộp thoại) đang mở --}}
                    <button type="button" class="btn btn-white" data-dismiss="modal">{{ __('button.close') }}</button>
                    <button type="submit" name="create" value="create"
                        class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
