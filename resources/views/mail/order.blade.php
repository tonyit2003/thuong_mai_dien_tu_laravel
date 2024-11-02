<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ __('mail.order_mail') }}</title>
</head>

<body>
    <div style="padding: 30px 10px; max-width: 900px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="text-transform: uppercase; font-weight: 700;">{{ __('info.order_successful') }}</h2>
            <div style="text-align: center;">
                <a href="{{ route('home.index') }}"
                    style="display: inline-block; padding: 10px 25px; background: #007bff; border-radius: 16px; cursor: pointer; color: #fff;">{{ __('info.explore_more_products') }}</a>
            </div>
        </div>

        <div style="margin-bottom: 40px;">
            <h2 style="text-align: center; margin-bottom: 30px;">
                <span style="text-transform: uppercase; font-weight: 700;">{{ __('info.order_info') }}</span>
            </h2>

            <div style="border: 1px solid #000; padding: 15px 20px; border-radius: 16px;">
                <div style="margin-bottom: 30px; text-align: center;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="width: 33%;"></div>
                        <div
                            style="width: 33%; border: 1px solid #000; border-radius: 16px; padding: 10px 15px; font-weight: 700; font-size: 16px; text-align: center;">
                            {{ __('info.order_title') }} #{{ $data['order']->code }}
                        </div>
                        <div style="width: 33%; font-size: 16px; font-weight: bold; text-align: center;">
                            {{ convertDateTime($data['order']->created_at) }}
                        </div>
                    </div>
                </div>

                <div>
                    <table style="width: 100%; border-spacing: 0; background: #d9d9d9; border-collapse: inherit;">
                        <thead>
                            <tr>
                                <th
                                    style="color: #fff; background: #007bff; font-weight: 500; font-size: 14px; vertical-align: middle; border-bottom: 2px solid #dee2e6; text-align: left; padding: 12px 15px; width: 40%;">
                                    {{ __('info.product_name') }}</th>
                                <th
                                    style="color: #fff; background: #007bff; font-weight: 500; font-size: 14px; vertical-align: middle; border-bottom: 2px solid #dee2e6; text-align: center; padding: 12px 15px; width: 15%;">
                                    {{ __('info.quantity') }}</th>
                                <th
                                    style="color: #fff; background: #007bff; font-weight: 500; font-size: 14px; vertical-align: middle; border-bottom: 2px solid #dee2e6; text-align: right; padding: 12px 15px; width: 25%;">
                                    {{ __('info.selling_price') }}</th>
                                <th
                                    style="color: #fff; background: #007bff; font-weight: 500; font-size: 14px; vertical-align: middle; border-bottom: 2px solid #dee2e6; text-align: right; padding: 12px 15px; width: 20%;">
                                    {{ __('info.money') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $initialTotal = 0;
                            @endphp
                            @foreach ($data['orderProducts'] as $key => $val)
                                @php
                                    $initialTotal += $val->price * $val->quantity;
                                @endphp
                                <tr style="background-color: #d9d9d9;">
                                    <td
                                        style="padding: 12px 15px; vertical-align: middle; font-size: 14px; color: #000;">
                                        {{ $val->name }}</td>
                                    <td
                                        style="padding: 12px 15px; vertical-align: middle; font-size: 14px; color: #000; text-align: center;">
                                        {{ $val->quantity }}</td>
                                    <td
                                        style="padding: 12px 15px; vertical-align: middle; font-size: 14px; color: #000; text-align: right;">
                                        {{ formatCurrency($val->price) }}</td>
                                    <td
                                        style="padding: 12px 15px; vertical-align: middle; font-size: 14px; color: #000; text-align: right;">
                                        <strong>{{ formatCurrency($val->price * $val->quantity) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: #fff;">
                            <tr>
                                <td colspan="3" style="padding: 8px;">{{ __('info.discount_code') }}</td>
                                <td style="padding: 8px; text-align: right;">
                                    <strong>{{ $data['order']->promotion['code'] ?? '' }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 8px;">{{ __('info.total_product_value') }}</td>
                                <td style="padding: 8px; text-align: right;">
                                    <strong>{{ formatCurrency($initialTotal) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 8px;">{{ __('info.total_promotional_value') }}</td>
                                <td style="padding: 8px; text-align: right;"><strong>-
                                        {{ formatCurrency($data['order']->promotion['discount'] ?? 0) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="padding: 8px;">{{ __('info.shipping_fee') }}</td>
                                <td style="padding: 8px; text-align: right;"><strong>{{ formatCurrency(0) }}</strong>
                                </td>
                            </tr>
                            <tr style="font-weight: bold; font-size: 24px;">
                                <td colspan="3" style="padding: 8px;"><span>{{ __('info.total payment') }}</span>
                                </td>
                                <td style="padding: 8px; text-align: right;">
                                    <strong>{{ formatCurrency($data['order']->totalPrice) }}</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h2 style="text-align: center; margin-bottom: 30px;">
                <span
                    style="text-transform: uppercase; font-weight: 700;">{{ __('info.delivery_information_payment') }}</span>
            </h2>
            <div style="border: 1px solid #000; padding: 15px 20px; border-radius: 16px;">
                <div style="margin-bottom: 15px; font-weight: 500;">
                    {{ __('info.recipient_name') }}: <span>{{ $data['order']->fullname }}</span>
                </div>
                <div style="margin-bottom: 15px; font-weight: 500;">
                    {{ __('info.email') }}: <span>{{ $data['order']->email }}</span>
                </div>
                <div style="margin-bottom: 15px; font-weight: 500;">
                    {{ __('info.address') }}:
                    <span>{{ getAddress($data['order']->province_id, $data['order']->district_id, $data['order']->ward_id, $data['order']->address) }}</span>
                </div>
                <div style="margin-bottom: 15px; font-weight: 500;">
                    {{ __('info.phone') }}: <span>{{ $data['order']->phone }}</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
