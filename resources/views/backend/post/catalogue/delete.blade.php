@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('post.catalogue.destroy', $postCatalogue->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Xác nhận xóa nhóm bài viết
                    </div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa nhóm bài viết có tên là: <span
                                class="text-danger">{{ $postCatalogue->name }}</span> ?
                        </p>
                        <p>Lưu ý: Bạn sẽ không thể khôi phục lại nhóm bài viết sau khi xóa.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row mb15">
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        Tên nhóm bài viết
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $postCatalogue->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        Tên viết tắt
                                    </label>
                                    <input readonly type="text" name="canonical"
                                        value="{{ old('canonical', $postCatalogue->canonical ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="Xóa nhóm bài viết" />
        </div>
    </div>
</form>
