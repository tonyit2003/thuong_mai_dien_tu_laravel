<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin phiếu đặt hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .email-container {
            width: 100%;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .email-content {
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
        }

        .company-info {
            width: 50%;
            text-align: left;
            line-height: 1.6;
        }

        .logo-container {
            width: 100%;
            text-align: right;
        }

        .logo-container img {
            height: auto;
        }

        .receipt-id {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .email-header {
            text-align: center;
            padding-bottom: 20px;
        }

        .email-header h1 {
            font-size: 24px;
            color: #333333;
            margin: 0;
        }

        .email-header p {
            font-size: 14px;
            color: #666666;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #dddddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .price-column {
            width: 100px;
            text-align: right;
        }

        .email-footer {
            text-align: center;
            color: #888888;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-content">
            <div class="header-container">
                <div class="company-info">
                    <p>Mã số thuế: {{ $system['contact_tax'] }}</p>
                    <p>Địa chỉ: {{ $system['contact_address'] }}</p>
                    <p>Số điện thoại: {{ $system['contact_phone'] }}</p>
                    <p>Website: {{ $system['contact_website'] }}</p>
                    <p>Email: {{ $system['contact_email'] }}</p>
                </div>
                <div class="logo-container">
                    <img src="{{ $system['homepage_logo'] }}" alt="Logo">
                </div>
            </div>
            <hr>
            <p class="receipt-id">Mã phiếu: {{ $productReceipt->id }}</p>

            <div class="email-header">
                <h1>Thông tin phiếu đặt hàng</h1>
                <p>Ngày {{ now()->day }} tháng {{ now()->month }} năm {{ now()->year }}</p>
            </div>
            <hr>
            <p>Kính gửi: {{ $productReceipt->suppliers->name }}</p>
            <p>Chúng tôi xin chân thành cảm ơn quý công ty đã xác nhận đơn hàng từ {{ $system['homepage_company'] }}
            <p>
                Chúng tôi mong muốn nhận được hàng vào ngày dự kiến:
                <strong>
                    {{ \Carbon\Carbon::parse($productReceipt->expected_delivery_date)->format('d/m/Y') }}
                </strong>
                để đảm bảo tiến độ kinh doanh và phục vụ khách hàng một cách tốt nhất.
            </p>
            <p>Xin quý công ty hỗ trợ sắp xếp và xác nhận lịch giao hàng sớm nhất có thể.</p>
            <p>Dưới đây là các thông tin chi tiết về đơn đặt hàng mà chúng tôi muốn nhận được:</p>
            <table>
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Phiên bản</th>
                        <th class="text-center">Số lượng</th>
                        <th class="price-column">Giá (VND)</th>
                    </tr>
                </thead>
                @php
                    $total = $formattedDetails->sum(function ($detail) {
                        return $detail['price'] * $detail['quantity'];
                    });
                @endphp
                <tbody>
                    @if ($formattedDetails->isNotEmpty())
                        @foreach ($formattedDetails->take(6) as $key => $detail)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $detail['product_name'] }}</td>
                                <td>{{ $detail['variant_name'] }}</td>
                                <td class="text-center">{{ $detail['quantity'] }}</td>
                                <td class="price-column">{{ number_format($detail['price'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach

                        {{-- Hiển thị dòng "..." nếu có nhiều hơn 6 sản phẩm --}}
                        @if ($formattedDetails->count() > 6)
                            <tr>
                                <td>...</td>
                                <td>...</td>
                                <td>...</td>
                                <td>...</td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="4">Không có sản phẩm nào.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" style="text-align: right;">Tổng tiền: {{ number_format($total, 0, ',', '.') }} VND</th>
                    </tr>
                </tfoot>
            </table>

            <p>Cảm ơn bạn đã hợp tác với chúng tôi!</p>
            <p>Trân trọng,<br>{{ $system['homepage_company'] }}</p>
        </div>
        <div class="email-footer">
            <p>{{ $system['homepage_company'] }} xin chân thành cảm ơn quý nhà cung cấp đã nhận được đơn hàng này. Chúng tôi mong nhận được sự hợp
                tác và phản hồi sớm từ quý vị.</p>
        </div>
    </div>
</body>

</html>
