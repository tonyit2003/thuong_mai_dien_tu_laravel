@php
    $link = isset($config['type']) && $config['type'] == 'index' ? route('order.index') : route('order.outOfStock');
@endphp
<form method="GET" action="{{ $link }}">
    <div class="filter-wrapper">
        <div class="uk-flex uk-flex-middle uk-flex-space-between">
            <div class="uk-flex uk-flex-middle">
                @include('backend.dashboard.component.perPage')
                <div class="date-item-box">
                    <input readonly type="text" name="created_at" id="" value=""
                        class="rangepicker form-control" placeholder="{{ __('form.choose_range_date') }}">
                </div>
            </div>
            <div class="action">
                <div class="uk-flex uk-flex-middle">
                    @if (isset($config['type']) && $config['type'] == 'index')
                        <div class="mr10">
                            @foreach (__('statusOrder') as $key => $val)
                                @php
                                    ${$key} = request($key) != null ? request($key) : -1;
                                @endphp
                                <select name={{ $key }} class="form-control mr10 setupSelect2" id="">
                                    @foreach ($val as $index => $item)
                                        <option {{ ${$key} == $index ? 'selected' : '' }} value="{{ $index }}">
                                            {{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            @endforeach
                        </div>
                    @endif
                    @include('backend.dashboard.component.keyword')
                </div>
            </div>
        </div>
    </div>
</form>
