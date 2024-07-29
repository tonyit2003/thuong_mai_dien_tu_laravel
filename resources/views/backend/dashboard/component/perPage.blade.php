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
