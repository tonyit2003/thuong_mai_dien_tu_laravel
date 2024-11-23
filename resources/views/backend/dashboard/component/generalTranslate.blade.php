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
                    <input type="text" name="translate_name" value="{{ old('translate_name', $model->name ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
    </div>
</div>
