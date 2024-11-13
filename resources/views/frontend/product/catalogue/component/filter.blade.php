<div class="filter">
    <div class="uk-flex uk-flex-middle uk-flex-space-between">
        <div class="filter-widget">
            <div class="uk-flex uk-flex-middle">
                <a href="" class="view-grid active">
                    <i class="fi-rs-grid"></i>
                </a>
                <a href="" class="view-grid view-list">
                    <i class="fi-rs-list"></i>
                </a>
                <div class="filter-button ml10 mr20">
                    <a href="" class="btn-filter uk-flex uk-flex-middle">
                        <i class="fi-rs-filter mr5"></i>
                        <span>{{ __('userPage.filter') }}</span>
                    </a>
                </div>
                <div class="perpage uk-flex uk-flex-middle">
                    <div class="filter-text">{{ __('userPage.display') }}</div>
                    <select name="perpage" id="perpage" class="nice-select">
                        @for ($i = 20; $i <= 100; $i += 20)
                            <option value="{{ $i }}">{{ $i }} {{ __('unit.product') }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="sorting">
            <select name="sort" id="" class="nice-select filtering" style="display: none">
                <option value="">{{ __('userPage.sort.by') }}</option>
                <option value="price:asc">{{ __('userPage.sort.price_asc') }}</option>
                <option value="price:desc">{{ __('userPage.sort.price_desc') }}</option>
                {{-- <option value="title:asc">{{ __('userPage.sort.title_asc') }}</option>
                <option value="title:desc">{{ __('userPage.sort.title_desc') }}</option> --}}
            </select>
        </div>
    </div>
</div>
