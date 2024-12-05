<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('form.general_info') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.title') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="translate_name"
                        value="{{ old('translate_name', $model->name ?? translateContent($originalModel->name ?? '')) }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.short_description') }}
                    </label>
                    <textarea id="description_1" type="text" name="translate_description" class="form-control ck-editor" placeholder=""
                        autocomplete="off" data-height="150">{{ old('translate_description', $model->description ?? translateContent($originalModel->description ?? '')) }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <label for="" class="control-label text-left">
                            {{ __('form.content') }}
                        </label>
                        <a href="#" class="multipleUploadImageCkeditor" data-target="ckContent">
                            {{ __('form.upload_multiple_images') }}
                        </a>
                    </div>
                    <textarea id="ckContent_1" type="text" name="translate_content" class="form-control ck-editor" placeholder=""
                        autocomplete="off" data-height="500">{{ old('translate_content', $model->content ?? translateContentHasImage($originalModel->content ?? '')) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
