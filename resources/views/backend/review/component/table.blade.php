<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 5%">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center" style="width: 10%">
                {{ __('table.name') }}
            </th>
            <th class="text-center" style="width: 15%">
                {{ __('table.email') }}
            </th>
            <th class="text-center">
                {{ __('table.content') }}
            </th>
            <th class="text-center" style="width: 10%">
                {{ __('table.star_score') }}
            </th>
            <th class="text-center" style="width: 15%">
                {{ __('table.object') }}
            </th>
            <th class="text-center" style="width: 8%">
                {{ __('table.status') }}
            </th>
            <th class="text-center" style="width: 5%">
                {{ __('table.actions') }}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($reviews) && is_object($reviews))
            @foreach ($reviews as $review)
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $review->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $review->fullname }}
                    </td>
                    <td>
                        {{ $review->email }}
                    </td>
                    <td>
                        {{ $review->content }}
                    </td>
                    <td class="text-center">
                        {!! generateStar($review->score) !!}
                    </td>
                    @php
                        $canonical = isset($review->product_canonical)
                            ? write_url($review->product_canonical, true, false) .
                                '/uuid=' .
                                $review->variant_uuid .
                                config('apps.general.suffix')
                            : '';
                    @endphp
                    <td class="text-center">
                        <a href="{{ $canonical }}" target="_blank">{{ __('table.click_to_object') }}</a>
                    </td>
                    <td class="text-center js-switch-{{ $review->id }}">
                        <input type="checkbox" value="{{ $review->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $review->id }}" {{ $review->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <div class="ibox-tools-button">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="color: #000">
                                <strong style="min-width: 0px">...</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-review" style="font-size: 13px; left: -170px">
                                <li>
                                    <a href="{{ route('review.delete', $review->id) }}">
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

{{ $reviews->links('pagination::bootstrap-4') }}
