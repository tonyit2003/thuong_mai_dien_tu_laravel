<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.source_name') }}
            </th>
            <th class="text-center">
                {{ __('table.keyword') }}
            </th>
            <th class="text-center">
                {{ __('table.description') }}
            </th>
            <th class="text-center" style="width: 100px">
                {{ __('table.status') }}
            </th>
            <th class="text-center" style="width: 100px">
                {{ __('table.actions') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($sources) && is_object($sources))
            @foreach ($sources as $source)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $source->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $source->name }}
                    </td>
                    <td>
                        {{ $source->keyword }}
                    </td>
                    <td>
                        {{-- html_entity_decode(): chuyển đổi các thực thể HTML (HTML entities) về dạng ký tự tương ứng. Ví dụ, &amp; sẽ được chuyển thành ký tự &, &lt; thành ký tự <, và &gt; thành >. --}}
                        {{-- strip_tags(): loại bỏ tất cả các thẻ HTML khỏi chuỗi đầu vào, chỉ giữ lại nội dung văn bản bên trong các thẻ.  --}}
                        {{ strip_tags(html_entity_decode($source->description)) }}
                    </td>
                    <td class="text-center js-switch-{{ $source->id }}">
                        <input type="checkbox" value="{{ $source->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $source->id }}" {{ $source->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('source.edit', $source->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('source.delete', $source->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $sources->links('pagination::bootstrap-4') }}
