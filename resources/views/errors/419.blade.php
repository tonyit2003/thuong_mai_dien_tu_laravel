<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERROR 404</title>
    <base href="{{ config('app.url') }}">
    <link rel="icon" type="image/png" href="userfiles/image/logo/logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #30425B;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            max-width: 1400px;
            width: 100%;
            background-color: #ffffff;
            overflow: hidden;
        }

        .error-img {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .error-img img {
            max-width: 600px;
        }

        .error-content {
            flex: 2;
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .error-content h3 {
            font-size: 32px;
            font-weight: bold;
            color: #2F80ED;
            margin-bottom: 15px;
        }

        .error-content .txt {
            font-size: 20px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-img">
            <a href="{{ route('home.index') }}"><img src="backend/img/419.png" alt="419 Page Expired"></a>
        </div>
        <div class="error-content">
            <h3>{{ __('error.419') }}</h3>
            <div class="txt">{{ __('error.no_419') }}</div>
        </div>
    </div>
</body>

</html>
