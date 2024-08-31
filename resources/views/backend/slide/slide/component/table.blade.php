<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center" style="width: 150px">
                {{ __('table.catalogue_name') }}
            </th>
            <th class="text-center" style="width: 150px">
                {{ __('table.keyword') }}
            </th>
            <th class="text-center">
                {{ __('table.image_list') }}
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
        @if (isset($slides) && is_object($slides))
            @foreach ($slides as $slide)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $slide->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $slide->name }}
                    </td>
                    <td>
                        {{ $slide->keyword }}
                    </td>
                    <td class="uk-flex uk-flex-wrap">
                        @foreach ($slide->item[$currentLanguage] as $index => $item)
                            <span style="margin-right: 10px; margin-bottom: 10px;" class="image img-cover">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            </span>
                        @endforeach
                    </td>
                    <td class="text-center js-switch-{{ $slide->id }}">
                        <input type="checkbox" value="{{ $slide->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $slide->id }}" {{ $slide->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('slide.edit', $slide->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('slide.delete', $slide->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $slides->links('pagination::bootstrap-4') }}
