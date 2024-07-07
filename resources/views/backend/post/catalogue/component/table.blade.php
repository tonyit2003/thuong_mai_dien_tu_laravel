<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">Tên nhóm bài viết</th>
            <th class="text-center" style="width: 100px">Tình trạng</th>
            <th class="text-center" style="width: 100px">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($postCatalogues) && is_object($postCatalogues))
            @foreach ($postCatalogues as $postCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ str_repeat('|----', $postCatalogue->level > 0 ? $postCatalogue->level - 1 : 0) . $postCatalogue->name }}
                    </td>
                    <td class="text-center js-switch-{{ $postCatalogue->id }}">
                        <input type="checkbox" value="{{ $postCatalogue->publish }}" class="js-switch status"
                            data-field="publish" data-model="PostCatalogue" data-modelId="{{ $postCatalogue->id }}"
                            {{ $postCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('post.catalogue.edit', $postCatalogue->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('post.catalogue.delete', $postCatalogue->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $postCatalogues->links('pagination::bootstrap-4') }}
