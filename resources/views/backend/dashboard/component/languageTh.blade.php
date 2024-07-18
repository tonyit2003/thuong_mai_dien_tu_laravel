@foreach ($languages as $language)
    @if ($currentCanonical == $language->canonical)
        @continue;
    @endif
    <th class="text-center" style="width: 100px">
        <span class="image image-scaledown language-flag">
            <img src="{{ $language->image }}" alt="">
        </span>
    </th>
@endforeach
