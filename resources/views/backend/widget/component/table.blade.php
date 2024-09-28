<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.widget_name') }}
            </th>
            <th class="text-center">
                {{ __('table.keyword') }}
            </th>
            <th class="text-center">
                {{ __('table.short_code') }}
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
        @if (isset($widgets) && is_object($widgets))
            @foreach ($widgets as $widget)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $widget->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $widget->name }}
                    </td>
                    <td>
                        {{ $widget->keyword }}
                    </td>
                    <td>
                        {{ $widget->short_code }}
                    </td>
                    @foreach ($allLanguages as $language)
                        @if ($currentCanonical == $language->canonical)
                            @continue;
                        @endif
                        @php
                            $translated = isset($widget->description[$language->id]) ? 1 : 0;
                        @endphp
                        <td class="text-center">
                            <a class="{{ $translated == 1 ? '' : 'text-danger' }}"
                                href="{{ route('widget.translate', ['languageId' => $language->id, 'id' => $widget->id]) }}">
                                {{ $translated == 1 ? __('table.translated') : __('table.not_yet_translated') }}
                            </a>
                        </td>
                    @endforeach
                    <td class="text-center js-switch-{{ $widget->id }}">
                        <input type="checkbox" value="{{ $widget->publish }}" class="js-switch status" data-field="publish"
                            data-model="{{ $config['model'] }}" data-modelId="{{ $widget->id }}" {{ $widget->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-user" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('widget.edit', $widget->id) }}">
                                        {{ __('table.update') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('widget.delete', $widget->id) }}">
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

{{ $widgets->links('pagination::bootstrap-4') }}
