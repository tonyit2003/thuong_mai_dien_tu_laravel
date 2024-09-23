<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.name') }}
            </th>
            <th class="text-center">
                {{ __('table.email') }}
            </th>
            <th class="text-center">
                {{ __('table.phone') }}
            </th>
            <th class="text-center">
                {{ __('table.address') }}
            </th>
            <th class="text-center">
                {{ __('table.user_group') }}
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
        @if (isset($users) && is_object($users))
            @foreach ($users as $user)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $user->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->phone }}
                    </td>
                    <td>
                        {{ $user->address }},
                        {{ optional($user->ward)->full_name }},
                        {{ optional($user->district)->full_name }},
                        {{ optional($user->province)->full_name }}
                    </td>
                    <td class="text-center">
                        {{ $user->user_catalogues->name }}
                    </td>
                    <td class="text-center js-switch-{{ $user->id }}">
                        <input type="checkbox" value="{{ $user->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $user->id }}" {{ $user->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('user.delete', $user->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $users->links('pagination::bootstrap-4') }}
