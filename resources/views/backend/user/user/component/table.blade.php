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
            <th class="text-center" style="width: 50px">
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
                        @php
                            $addressParts = [];

                            if (!empty($user->address)) {
                                $addressParts[] = $user->address;
                            }
                            if (!empty(optional($user->ward)->full_name)) {
                                $addressParts[] = optional($user->ward)->full_name;
                            }
                            if (!empty(optional($user->district)->full_name)) {
                                $addressParts[] = optional($user->district)->full_name;
                            }
                            if (!empty(optional($user->province)->full_name)) {
                                $addressParts[] = optional($user->province)->full_name;
                            }
                        @endphp

                        @if (!empty($addressParts))
                            {{ implode(', ', $addressParts) }}
                        @endif
                    </td>
                    <td>
                        {{ $user->user_catalogues->name }}
                    </td>
                    <td class="text-center js-switch-{{ $user->id }}">
                        <input type="checkbox" value="{{ $user->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $user->id }}" {{ $user->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('user.edit', $user->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.delete', $user->id) }}">
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

{{ $users->links('pagination::bootstrap-4') }}
