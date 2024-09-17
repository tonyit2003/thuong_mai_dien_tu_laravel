<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th class="text-center" style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox" />
            </th>
            <th class="text-center">
                {{ __('table.promotion_name') }}
            </th>
            <th class="text-center">
                {{ __('table.discount') }}
            </th>
            <th class="text-center">
                {{ __('table.information') }}
            </th>
            <th class="text-center">
                {{ __('table.start_date') }}
            </th>
            <th class="text-center">
                {{ __('table.end_date') }}
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
        @if (isset($promotions) && is_object($promotions))
            @foreach ($promotions as $promotion)
                @php
                    $status = '';
                    $currentDate = \Carbon\Carbon::now();
                    if ($promotion->endDate !== null) {
                        // phân tích một chuỗi ngày giờ thành một đối tượng Carbon
                        $startDate = \Carbon\Carbon::parse($promotion->startDate);
                        $endDate = \Carbon\Carbon::parse($promotion->endDate);
                        if ($endDate->lessThanOrEqualTo($startDate) || $endDate->lessThan($currentDate)) {
                            $status = '<span class="text-danger text-small"> (' . __('table.expired') . ')</span>';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">
                        <input type="checkbox" value="{{ $promotion->id }}" class="input-checkbox checkBoxItem" />
                    </td>
                    <td>
                        {{ $promotion->name }} {!! $status !!}
                        <div class="text-small text-success">{{ __('table.promotion_code') }}: {{ $promotion->code }}
                        </div>
                    </td>
                    <td>
                        <div class="discount-information text-center">
                            {!! renderDiscountInformation($promotion) !!}
                        </div>
                    </td>
                    <td>
                        <div>{{ __('module.promotion')[$promotion->method] }}</div>
                    </td>
                    <td>
                        {{ convertDateTime($promotion->startDate) }}
                    </td>
                    <td>
                        {{ $promotion->neverEndDate === 'accept' ? __('table.unlimited') : convertDateTime($promotion->endDate) }}
                    </td>
                    <td class="text-center js-switch-{{ $promotion->id }}">
                        <input type="checkbox" value="{{ $promotion->publish }}" class="js-switch status"
                            data-field="publish" data-model="{{ $config['model'] }}"
                            data-modelId="{{ $promotion->id }}" {{ $promotion->publish == 1 ? 'checked' : '' }} />
                    </td>
                    <td class="text-center">
                        <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-success">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{ route('promotion.delete', $promotion->id) }}" class="btn btn-danger">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{ $promotions->links('pagination::bootstrap-4') }}
