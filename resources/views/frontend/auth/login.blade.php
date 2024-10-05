<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>{{ __('info.login') }}</title>

    <!-- Meta tags -->
    <link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Style -->
    <link rel="stylesheet" href="frontend/auth/css/style.css" type="text/css" media="all" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <!-- login form -->
    <section class="w3l-login">
        <div class="overlay">
            <div class="wrapper">
                <div class="logo">
                    <a class="brand-logo" href="index.html"><strong>{{ $system['homepage_brand'] }}</strong></a>
                </div>
                <div class="form-section">
                    <h3>{{ __('info.login') }}</h3>
                    <h6>{{ __('info.notify') }}</h6>
                    <form action="{{ route('authClient.login') }}" method="post" class="signin-form">
                        @csrf
                        <label for="email">Email</label>
                        <div class="form-input">
                            <input type="email" id="email" name="email" placeholder="Email" autofocus
                                value="{{ old('email', 'caotancong2003@gmail.com') }}">
                        </div>
                        <label for="password">{{ __('info.password') }}</label>
                        <div class="form-input">
                            <input type="password" id="password" name="password" placeholder="Mật khẩu" value="123456">
                        </div>
                        <label class="check-remaind">
                            <input type="checkbox" id="remember">
                            <span class="checkmark"></span>
                            <p class="remember">{{ __('info.save_acc') }}</p>
                        </label>
                        <button type="submit" class="btn btn-primary theme-button mt-4">{{ __('info.login') }}</button>
                        <div class="new-signup">
                            <a href="#reload" class="signuplink">{{ __('info.forgot_password') }}</a>
                        </div>
                    </form>
                    <p class="signup">{{ __('info.question') }}
                        <a href="{{ route('authClient.register') }}" class="signuplink">
                            {{ __('info.register') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        <div id='stars'></div>
        <div id='stars2'></div>
        <div id='stars3'></div>

        <!-- copyright -->
        <div class="copy-right">
            <p><strong>Copyright</strong> {{ $system['homepage_copyright'] }}&copy; 2024-2030</p>
        </div>
        <!-- //copyright -->
    </section>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toast Notifications -->
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
        };

        // Thông báo thành công
        @if (session()->has('success'))
            toastr.success('{{ session('success') }}');
        @endif

        // Thông báo lỗi
        @if (session()->has('error'))
            toastr.error('{{ session('error') }}');
        @endif

        // Hiển thị lỗi request validation
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
        @endif
    </script>
    <!-- /login form -->
</body>

</html>
