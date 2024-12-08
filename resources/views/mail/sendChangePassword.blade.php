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
            <p>{{ __('mail.hi') }} <strong>{{ $user->name }}</strong>,</p>
            <h3>{{ __('mail.note') }}</h3>
            <p><strong>{{ __('mail.time') }}</strong> {{ $currentTime }}</p>
            <p><strong>{{ __('mail.browser') }}</strong> {{ $browser }}</p>
            <p><strong>{{ __('mail.system') }}</strong> {{ $platform }} ({{ $device }})</p>
            <p>{{ __('mail.note2') }}<strong>{{ __('mail.NO') }}</strong>{{ __('mail.note9') }}</p>
            <ul>
                <li>{{ __('mail.note3') }} {{ $system['homepage_company'] }}</li>
                <li>{{ __('mail.note4') }}</li>
            </ul>
            <p>{{ __('mail.note5') }}<strong><a href="{{ $link }}">{{ __('mail.here') }}</a></strong> {{ __('mail.timemax') }}</p>
            <p>{{ __('mail.note6') }},<br>{{ __('mail.note8') }}</p>
        </div>
        <!-- Footer -->
        <div class="email-footer">
            <p>{{ __('mail.note7') }}<a href="">{{ __('mail.here2') }}</a>.</p>
        </div>
    </div>
</body>

</html>
