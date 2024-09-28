<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.user_group_name') }}</th>
            <th class="text-center">{{ __('table.member_count') }}</th>
            <th class="text-center">{{ __('table.description') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($userCatalogues) && is_object($userCatalogues))
            @foreach ($userCatalogues as $userCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $userCatalogue->name }}
                    </td>
                    <td class="text-center">
                        {{-- xem thuộc tính bằng dd() --}}
                        {{ $userCatalogue->users_count }} {{ __('unit.members') }}
                    </td>
                    <td>
                        {{ $userCatalogue->description }}
                    </td>
                    <td class="text-center js-switch-{{ $userCatalogue->id }}">
                        <input type="checkbox" value="{{ $userCatalogue->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $userCatalogue->id }}"
                            {{ $userCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('user.catalogue.edit', $userCatalogue->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('user.catalogue.delete', $userCatalogue->id) }}">
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

{{ $userCatalogues->links('pagination::bootstrap-4') }}
