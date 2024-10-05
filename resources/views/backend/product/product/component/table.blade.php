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
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($products) && is_object($products))
            @foreach ($products as $product)
                <tr id="{{ $product->id }}">
                    <td class="text-center">
                        <input type="checkbox" value="{{ $product->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5">
                                <div class="img-cover image-product">
                                    <img src="{{ $product->image }}" alt="">
                                </div>
                            </div>
                            <div class="main-info">
                                <div class="name">
                                    <span class="maintitle">{{ $product->name }}</span>
                                </div>
                                <div class="catalogue">
                                    <span class="text-danger">{{ __('table.display_group') }}: </span>
                                    @foreach ($product->product_catalogues as $val)
                                        @foreach ($val->product_catalogue_language->where('language_id', $languageId) as $cat)
                                            <a href="{{ route('product.index', ['product_catalogue_id' => $val->id]) }}">{{ $cat->name }}</a>
                                            @if (!$loop->last)
                                                |
                                            @endif
                                        @endforeach
                                        @if (!$loop->last)
                                            |
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </td>
                    @include('backend.dashboard.component.languageTd', [
                        'model' => $product,
                        'modeling' => 'Product',
                    ])
                    <td>
                        <input type="text" name="order" class="form-control sort-order text-right" data-id="{{ $product->id }}"
                            data-model="{{ $config['model'] }}" value="{{ $product->order }}">
                    </td>
                    <td class="text-center js-switch-{{ $product->id }}">
                        <input type="checkbox" value="{{ $product->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $product->id }}" {{ $product->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('product.edit', $product->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('product.delete', $product->id) }}">
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

{{ $products->links('pagination::bootstrap-4') }}
