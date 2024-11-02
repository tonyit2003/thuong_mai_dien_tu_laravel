<form method="GET" action="{{ route('warranty.index') }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle" style="justify-content: flex-end;">
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    <div class="uk-search uk-flex uk-flex-middle mr10">
                        <div class="input-group">
                            <input value="{{ request('keyword') ?: old('keyword') }}" type="text" name="keyword" id=""
                                placeholder="{{ __('form.enter_phone') }}" class="form-control">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary mb0 btn-sm">
                                    {{ __('button.search') }}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
