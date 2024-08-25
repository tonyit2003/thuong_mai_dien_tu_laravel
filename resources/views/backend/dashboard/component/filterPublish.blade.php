@php
    $publish = request('publish') != null ? request('publish') : -1;
@endphp
<select name="publish" class="form-control mr10 setupSelect2" id="">
    @foreach (__('publish') as $key => $val)
        <option {{ $key == $publish ? 'selected' : '' }} value="{{ $key }}">
            {{ $val }}
        </option>
    @endforeach
</select>
