<form method="GET" action="{{ route('user.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="perpage">
                @php
                    $perpage = request('perpage') ?: old('perpage');
                @endphp
                <div class="uk-flex uk-flex-middle uk-flex-space-between">
                    <select name="perpage" id="" class="form-control input-sm perpage filter mr10">
                        @for ($i = 20; $i <= 200; $i += 20)
                            <option {{ $perpage == $i ? 'selected' : '' }} value="{{ $i }}">
                                {{ $i }} bản ghi</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <select name="user_catalogue_id" class="form-control mr10 setupSelect2 mr10" id="">
                        <option value="0" selected="selected">
                            Chọn nhóm thành viên
                        </option>
                        <option value="1">Quản trị viên</option>
                    </select>
                    <div class="uk-search uk-flex uk-flex-middle mr10 ml10">
                        <div class="input-group">
                            {{-- VT ?: VP => hiển thị VT nếu VT không null, VT null thì hiển thị VP --}}
                            {{-- request('keyword'): lấy dữ liệu từ yêu cầu HTTP hiện tại --}}
                            <input value="{{ request('keyword') ?: old('keyword') }}" type="text" name="keyword"
                                id="" placeholder="Nhập từ khóa..." class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" name="search" value="search"
                                    class="btn btn-primary mb0 btn-sm">Tìm
                                    kiếm</button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('user.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        Thêm mới thành viên
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
