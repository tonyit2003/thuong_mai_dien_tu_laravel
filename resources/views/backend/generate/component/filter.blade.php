<form method="GET" action="{{ route('generate.index') }}">
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
                                {{ $i }} {{ __('unit.records') }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            {{-- VT ?: VP => hiển thị VT nếu VT không null, VT null thì hiển thị VP --}}
                            {{-- request('keyword'): lấy dữ liệu từ yêu cầu HTTP hiện tại --}}
                            <input value="{{ request('keyword') ?: old('keyword') }}" type="text" name="keyword"
                                id="" placeholder="{{ __('form.enter_keyword') }}" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary mb0 btn-sm">
                                    {{ __('button.search') }}
                                </button>
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('generate.create') }}" class="btn btn-danger">
                        <i class="fa fa-plus mr5"></i>
                        {{ __('generate.create.title') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</form>
