<div class="ibox">
    <div class="ibox-title">
        <h5>Thông tin chung</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        Tiêu đề nhóm bài viết
                        <span class="text-danger">(*)</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $postCatalogue->name ?? '') }}"
                        class="form-control" placeholder="" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        Mô tả ngắn
                        <span class="text-danger">(*)</span>
                    </label>
                    <textarea id="description" type="text" name="description"
                        value="{{ old('description', $postCatalogue->description ?? '') }}" class="form-control ck-editor" placeholder=""
                        autocomplete="off"></textarea>
                </div>
            </div>
        </div>
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        Nội dung
                        <span class="text-danger">(*)</span>
                    </label>
                    <textarea id="content" type="text" name="content" value="{{ old('content', $postCatalogue->content ?? '') }}"
                        class="form-control ck-editor" placeholder="" autocomplete="off"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
