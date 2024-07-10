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
        @if (isset($posts) && is_object($posts))
            @foreach ($posts as $post)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $post->name }}
                    </td>
                    <td class="text-center js-switch-{{ $post->id }}">
                        <input type="checkbox" value="{{ $post->publish }}" class="js-switch status"
                            data-field="publish" data-model="PostCatalogue" data-modelId="{{ $post->id }}"
                            {{ $post->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('post.delete', $post->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $posts->links('pagination::bootstrap-4') }}
