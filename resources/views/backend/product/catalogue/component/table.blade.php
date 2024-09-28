<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.title') }}
            </th>
            @include('backend.dashboard.component.languageTh')
            <th class="text-center" style="width: 100px">
                {{ __('table.status') }}
            </th>
            <th class="text-center" style="width: 50px">
                {{ __('table.actions') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($productCatalogues) && is_object($productCatalogues))
            @foreach ($productCatalogues as $productCatalogue)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $productCatalogue->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ str_repeat('|----', $productCatalogue->level > 0 ? $productCatalogue->level - 1 : 0) . $productCatalogue->name }}
                    </td>
                    @include('backend.dashboard.component.languageTd', [
                        'model' => $productCatalogue,
                        'modeling' => 'ProductCatalogue',
                    ])
                    <td class="text-center js-switch-{{ $productCatalogue->id }}">
                        <input type="checkbox" value="{{ $productCatalogue->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $productCatalogue->id }}"
                            {{ $productCatalogue->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('product.catalogue.edit', $productCatalogue->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.catalogue.delete', $productCatalogue->id) }}">
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

{{ $productCatalogues->links('pagination::bootstrap-4') }}
