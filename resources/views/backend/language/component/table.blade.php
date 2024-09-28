<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th style="width: 100px" class="text-center">{{ __('table.image') }}</th>
            <th class="text-center">{{ __('table.language_name') }}</th>
            <th class="text-center">{{ __('table.language_code') }}</th>
            <th class="text-center">{{ __('table.description') }}</th>
            <th class="text-center" style="width: 100px">{{ __('table.status') }}</th>
            <th class="text-center" style="width: 50px">{{ __('table.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($languages) && is_object($languages))
            @foreach ($languages as $language)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        <span class="image img-cover">
                            <img src="{{ $language->image }}" alt="">
                        </span>
                    </td>
                    <td>
                        {{ $language->name }}
                    </td>
                    <td class="text-center">
                        {{ $language->canonical }}
                    </td>
                    <td>
                        {{ $language->description }}
                    </td>
                    <td class="text-center js-switch-{{ $language->id }}">
                        <input type="checkbox" value="{{ $language->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $language->id }}"
                            {{ $language->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('language.edit', $language->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('language.delete', $language->id) }}">
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

{{ $languages->links('pagination::bootstrap-4') }}
