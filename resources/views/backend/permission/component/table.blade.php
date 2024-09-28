<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.permission_name') }}</th>
            <th class="text-center">{{ __('table.permission_canonical') }}</th>
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($permissions) && is_object($permissions))
            @foreach ($permissions as $permission)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $permission->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $permission->name }}
                    </td>
                    <td>
                        {{ $permission->canonical }}
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('permission.edit', $permission->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('permission.delete', $permission->id) }}">
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

{{ $permissions->links('pagination::bootstrap-4') }}
