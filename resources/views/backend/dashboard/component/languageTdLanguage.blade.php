@foreach ($allLanguages as $language)
    @if ($currentCanonical == $language->canonical)
        @continue;
    @endif
    <td class="text-center">
        @php
            $translated = $model->languages->contains('id', $language->id);
        @endphp
        <a class="{{ $translated ? '' : 'text-danger' }}"
            href="{{ route('language.translateLanguage', ['id' => $model->id, 'languageId' => $language->id, 'model' => $modeling]) }}">
            {{ $translated ? __('table.translated') : __('table.not_yet_translated') }}
        </a>
    </td>
@endforeach
