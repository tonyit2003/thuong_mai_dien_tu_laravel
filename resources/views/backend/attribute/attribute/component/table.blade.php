<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">{{ __('table.title') }}</th>
            @include('backend.dashboard.component.languageTh')
            <th class="text-center" style="width: 8rem">{{ __('table.index') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($attributes) && is_object($attributes))
            @foreach ($attributes as $attribute)
                <tr id="{{ $attribute->id }}">
                    <td class="text-center">
                        <input type="checkbox" value="{{ $attribute->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="main-info">
                                <div class="name">
                                    <span class="maintitle">{{ $attribute->name }}</span>
                                </div>
                                <div class="catalogue">
                                    <span class="text-danger">{{ __('table.display_group') }}: </span>
                                    @foreach ($attribute->attribute_catalogues as $val)
                                        @foreach ($val->attribute_catalogue_language as $cat)
                                            <a
                                                href="{{ route('attribute.index', ['attribute_catalogue_id' => $val->id]) }}">{{ $cat->name }}</a>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd', [
                        'model' => $attribute,
                        'modeling' => 'Attribute',
                    ])
                    <td>
                        <input type="text" name="order" class="form-control sort-order text-right"
                            data-id="{{ $attribute->id }}" data-model="{{ $config['model'] }}"
                            value="{{ $attribute->order }}">
                    </td>
                    <td class="text-center js-switch-{{ $attribute->id }}">
                        <input type="checkbox" value="{{ $attribute->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $attribute->id }}" {{ $attribute->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('attribute.edit', $attribute->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('attribute.delete', $attribute->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $attributes->links('pagination::bootstrap-4') }}
