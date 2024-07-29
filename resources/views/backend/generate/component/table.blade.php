<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.module_name') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($generates) && is_object($generates))
            @foreach ($generates as $generate)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $generate->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $generate->name }}
                    </td>
                    <td class="text-center">
                        <a href="{{ route('generate.edit', $generate->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('generate.delete', $generate->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $generates->links('pagination::bootstrap-4') }}
