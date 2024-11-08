<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Notification</title>
    <script src="https://cdn.jsdelivr.net/npm/ua-parser-js@0.7.35/dist/ua-parser.min.js"></script>
    <base href="{{ config('app.url') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-header img {
            width: 80px;
        }

        .email-content {
            font-size: 14px;
        }

        .email-content h3 {
            color: #ff5722;
            margin-bottom: 10px;
        }

        .email-content strong {
            font-weight: bold;
        }

        .email-content ul {
            list-style: disc;
            padding-left: 20px;
        }

        .email-content ul li {
            margin-bottom: 5px;
        }

        .email-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff5722;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .email-footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .email-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="{{ $system['homepage_logo'] }}" style="width: 160px">
        </div>
        <!-- Content -->
        <div class="email-content">
            <p>Chào bạn <strong>{{ $user->name }}</strong>,</p>
            <h3>Lưu ý: Tài khoản của bạn đang được truy cập</h3>
            <p><strong>Thời gian truy cập:</strong> {{ $currentTime }}</p>
            <p><strong>Trình duyệt:</strong> {{ $browser }}</p>
            <p><strong>Hệ điều hành (Thiết bị):</strong> {{ $platform }} ({{ $device }})</p>

            <p>Hãy cảnh giác với các hành vi lừa đảo. Tuyệt đối <strong>KHÔNG</strong> cho phép đăng nhập và từ chối các cuộc gọi:</p>
            <ul>
                <li>Tự nhận gọi từ {{ $system['homepage_company'] }}</li>
                <li>Thông báo rằng bạn vừa trúng thưởng</li>
            </ul>

            <p><strong>Nếu bạn đang thực hiện đăng nhập, vui lòng xác nhận <a href="{{ route('customer.change') }}" class="">TẠI
                        ĐÂY</a></strong> (hiệu lực trong
                vòng 10 phút).</p>

            <p>Trân trọng,<br>Đội ngũ Hỗ trợ</p>
        </div>
        <!-- Footer -->
        <div class="email-footer">
            <p>Cần hỗ trợ? Liên hệ chúng tôi <a href="">tại đây</a>.</p>
        </div>
    </div>
</body>

</html>
