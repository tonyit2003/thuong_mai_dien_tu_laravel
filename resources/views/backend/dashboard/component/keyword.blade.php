<div class="uk-search uk-flex uk-flex-middle mr10">
    <div class="input-group">
        {{-- VT ?: VP => hiển thị VT nếu VT không null, VT null thì hiển thị VP --}}
        {{-- request('keyword'): lấy dữ liệu từ yêu cầu HTTP hiện tại --}}
        <input value="{{ request('keyword') ?: old('keyword') }}" type="text" name="keyword" id=""
            placeholder="{{ __('form.enter_keyword') }}" class="form-control">
        <span class="input-group-btn">
            <button type="submit" class="btn btn-primary mb0 btn-sm">
                {{ __('button.search') }}
            </button>
        </span>
    </div>
</div>
