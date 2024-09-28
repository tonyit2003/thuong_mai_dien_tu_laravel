<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.module_name') }}</th>
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
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
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('generate.edit', $generate->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('generate.delete', $generate->id) }}">
                                        {{ __('table.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $generates->links('pagination::bootstrap-4') }}
