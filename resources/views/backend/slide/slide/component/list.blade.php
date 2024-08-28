<div class="ibox">
    <div class="ibox-title ibox-title-slide">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <h5>{{ __('form.list_slide') }}</h5>
            <button type="button" class="addSlide btn btn-success">{{ __('form.add_slide') }}</button>
        </div>
    </div>
    <div class="ibox-content">
        <div id="sortable" class="row slide-list sortui ui-sortable">
            <div class="text-danger slide-notification">{{ __('form.no_image_selected') }}</div>
        </div>
    </div>
</div>

<script>
    var generalInfo = "{{ __('form.general_info') }}";
    var seo = "{{ __('form.seo') }}";
    var description = "{{ __('form.description') }}";
    var url = "{{ __('form.url') }}";
    var openInNewTab = "{{ __('form.open_in_new_tab') }}";
    var imageTitle = "{{ __('form.image_title') }}";
    var imageDescription = "{{ __('form.image_description') }}";
</script>
