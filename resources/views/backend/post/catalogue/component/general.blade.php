<div class="ibox">
    <div class="ibox-title">
        <h5>
            {{ __('messages.generalTitle') }}
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('messages.title') }}
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $postCatalogue->name ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb30">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        {{ __('messages.description') }}
                    </label>
                    <textarea id="description" type="text" name="description" class="form-control ck-editor" placeholder=""
                        autocomplete="off" data-height="150">{{ old('description', $postCatalogue->description ?? '') }}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="uk-flex uk-flex-middle uk-flex-space-between">
                        <label for="" class="control-label text-left">
                            {{ __('messages.content') }}
                        </label>
                        <a href="#" class="multipleUploadImageCkeditor" data-target="ckContent">
                            {{ __('messages.upload') }}
                        </a>
                    </div>
                    <textarea id="ckContent" type="text" name="content" class="form-control ck-editor" placeholder="" autocomplete="off"
                        data-height="500">{{ old('content', $postCatalogue->content ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
