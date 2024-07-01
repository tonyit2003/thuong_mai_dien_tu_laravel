@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['delete']['title']])
<form action="{{ route('user.destroy', $user->id) }}" method="post" class="box">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Xác nhận xóa thành viên
                    </div>
                    <div class="panel-description">
                        <p>Bạn đang muốn xóa thành viên có email là: <span class="text-danger">{{ $user->email }}</span>
                            ?</p>
                        <p>Lưu ý: Bạn sẽ không thể khôi phục lại thành viên sau khi xóa.</p>
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
                                        Email
                                    </label>
                                    <input readonly type="text" name="email"
                                        value="{{ old('email', $user->email ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-row">
                                    <label for="" class="control-label text-left">
                                        Họ tên
                                    </label>
                                    <input readonly type="text" name="name"
                                        value="{{ old('name', $user->name ?? '') }}" class="form-control" placeholder=""
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-right mb15">
            <input class="btn btn-danger" type="submit" name="send" value="Xóa thành viên" />
        </div>
    </div>
</form>
