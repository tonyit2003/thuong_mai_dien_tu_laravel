<div class="ibox">
    <div class="ibox-title">
        <h5>
            Chọn danh mục cha
        </h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        Chọn danh mục cha
                        <span class="text-danger">(*)</span>
                    </label>
                    <select name="post_catalogue_id" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option
                                {{ $key == old('post_catalogue_id', isset($post->post_catalogue_id) ? $post->post_catalogue_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @php
            $catalogue = [];
            if (isset($post)) {
                foreach ($post->post_catalogues as $key => $val) {
                    $catalogue[] = $val->id;
                }
            }
        @endphp
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <label for="" class="control-label text-left">
                        Chọn danh mục phụ
                    </label>
                    <select multiple name="catalogue[]" id="" class="form-control setupSelect2">
                        @foreach ($dropdown as $key => $val)
                            <option @if (is_array(old('catalogue', isset($catalogue) && count($catalogue) ? $catalogue : [])) &&
                                    in_array($key, old('catalogue', isset($catalogue) && count($catalogue) ? $catalogue : []))) selected @endif value="{{ $key }}">
                                {{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn ảnh đại diện</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="image img-cover img-target">
                        <img src="{{ old('image', $post->image ?? 'backend/img/no-photo.png') }}" alt="">
                    </span>
                    <input type="hidden" name="image" value="{{ old('image', $post->image ?? '') }}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình nâng cao</h5>
    </div>
    <div class="ibox-content">
        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <div class="mb15">
                        <select name="publish" id="" class="form-control setupSelect2 mb15">
                            @foreach (config('apps.general.publish') as $key => $val)
                                <option
                                    {{ $key == old('publish', isset($post->publish) ? $post->publish : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <select name="follow" id="" class="form-control setupSelect2 mb15">
                        @foreach (config('apps.general.follow') as $key => $val)
                            <option
                                {{ $key == old('follow', isset($post->follow) ? $post->follow : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
