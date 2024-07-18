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
                    <input type="text" name="name" value="{{ old('name', $model->name ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off" {{ isset($disabled) ? 'disabled' : '' }}>
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('form.short_description') }}
                    </label>
                    <textarea id="description" type="text" name="description" class="form-control ck-editor" placeholder=""
                        autocomplete="off" data-height="150" {{ isset($disabled) ? 'disabled' : '' }}>{{ old('description', $model->description ?? '') }}</textarea>
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
                    <textarea id="ckContent" type="text" name="content" class="form-control ck-editor" placeholder="" autocomplete="off"
                        data-height="500" {{ isset($disabled) ? 'disabled' : '' }}>{{ old('content', $model->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
