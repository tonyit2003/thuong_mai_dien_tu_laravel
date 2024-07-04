<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                CellphoneS - Điện thoại, laptop, tablet, phụ kiện chính hãng
            </div>
            <div class="canonical">
                https://cellphones.com.vn/
            </div>
            <div class="meta-description">
                Hệ thống 120 cửa hàng bán lẻ điện thoại, máy tính laptop, smartwatch, gia dụng, thiết bị
                IT, phụ kiện chính hãng - Giá tốt, trả góp 0%, giao miễn phí.
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Tiêu đề SEO</span>
                                <span class="count_meta_title">0 ký tự</span>
                            </div>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $postCatalogue->title ?? '') }}"
                            class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Từ khóa SEO</span>
                        </label>
                        <input type="text" name="keyword" value="{{ old('keyword', $postCatalogue->keyword ?? '') }}"
                            class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Mô tả SEO</span>
                                <span class="count_meta_description">0 ký tự</span>
                            </div>
                        </label>
                        <textarea type="text" name="meta_description"
                            value="{{ old('meta_description', $postCatalogue->meta_description ?? '') }}" class="form-control" placeholder=""
                            autocomplete="off"></textarea>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Đường dẫn</span>
                        </label>
                        <input type="text" name="canonical"
                            value="{{ old('canonical', $postCatalogue->canonical ?? '') }}" class="form-control"
                            placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
