<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Bảo Hành</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
            line-height: 1.6;
            color: #333333;
        }

        .email-body h2 {
            margin-top: 0;
        }

        .email-footer {
            background-color: #f9f9f9;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666666;
            border-top: 1px solid #dddddd;
        }

        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Xác Nhận Yêu Cầu Bảo Hành</h1>
        </div>
        <div class="email-body">
            <p>Kính chào <strong>{{ $to }}</strong>,</p>
            <p>Chúng tôi đã nhận được yêu cầu bảo hành sản phẩm của bạn. Dưới đây là thông tin chi tiết:</p>
            <table border="1" cellspacing="0" cellpadding="10" style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr>
                        <th style="background-color: #f2f2f2;text-align: center">Tên sản phẩm</th>
                        <th style="background-color: #f2f2f2;text-align: center">Ngày nhận hàng dự kiến</th>
                        <th style="background-color: #f2f2f2;text-align: center">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td style="width: 60%">{{ $item['product_name'] }}</td>
                            <td style="width: 20%; text-align: center">{{ \Carbon\Carbon::parse($item['date_of_receipt'])->format('d/m/Y') }}</td>
                            <td style="width: 20%">{{ $item['note'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p>Chúng tôi sẽ tiến hành kiểm tra và liên hệ với bạn nếu cần thêm thông tin.</p>
            <p>Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ <a href="{{ $system['contact_email'] }}">{{ $system['contact_email'] }}</a> hoặc gọi đến
                số
                <strong>{{ $system['contact_phone'] }}</strong>.
            </p>
            <p>Trân trọng,<br>
                Đội ngũ hỗ trợ khách hàng</p>
        </div>
        <div class="email-footer">
            <p>© 2024 Công ty {{ $system['homepage_company'] }}. Mọi quyền được bảo lưu.</p>
            <p><a href="#">Chính sách bảo hành</a> | <a href="#">Liên hệ</a></p>
        </div>
    </div>
</body>

</html>
