<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.menu_name') }}
            </th>
            <th class="text-center">
                {{ __('table.keyword') }}
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
        @if (isset($menuCatalogues) && is_object($menuCatalogues))
            @foreach ($menuCatalogues as $menuCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $menuCatalogue->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $menuCatalogue->name }}
                    </td>
                    <td>
                        {{ $menuCatalogue->keyword }}
                    </td>
                    <td class="text-center js-switch-{{ $menuCatalogue->id }}">
                        <input type="checkbox" value="{{ $menuCatalogue->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $menuCatalogue->id }}"
                            {{ $menuCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('menu.edit', $menuCatalogue->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('menu.delete', $menuCatalogue->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
