<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>{{ __('info.register') }}</title>

    <!-- Meta tags -->
    <link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="frontend/auth/css/style.css" type="text/css" media="all" />
</head>

<body>
    <!-- login form -->
    <section class="w3l-login">
        <div class="overlay">
            <div class="wrapper">
                <div class="logo">
                    <a class="brand-logo" href="{{ config('app.url') }}"><strong>T&C - SHOP</strong></a>
                </div>
                <div class="form-section" style="max-width: 500px">
                    <h3>{{ __('info.register') }}</h3>
                    <h6>{{ __('info.notify') }}</h6>
                    <form action="{{ route('authClient.signup') }}" method="post" class="signin-form">
                        @csrf
                        <label for="">{{ __('info.name') }}</label>
                        <div class="form-input">
                            <input type="text" name="name" placeholder="{{ __('info.name') }}" autofocus
                                value="{{ old('name') }}">
                        </div>
                        <label for="">{{ __('form.email') }}</label>
                        <div class="form-input">
                            <input type="email" name="email" placeholder="Email" autofocus
                                value="{{ old('email') }}">
                        </div>
                        <label for="">{{ __('info.password') }}</label>
                        <div class="form-input">
                            <input type="password" name="password" placeholder="{{ __('info.password') }}">
                        </div>
                        <label for="">{{ __('info.re_password') }}</label>
                        <div class="form-input">
                            <input type="password" name="re_password" placeholder="{{ __('info.re_password') }}">
                        </div>
                        <label class="check-remaind">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            <p class="remember">{{ __('info.OK') }}</p>
                        </label>
                        <button type="submit"
                            class="btn btn-primary theme-button mt-4">{{ __('info.register') }}</button>
                        <div class="new-signup">
                            <a href="#reload" class="signuplink">{{ __('info.forgot_password') }}</a>
                        </div>
                    </form>
                    <p class="signup">{{ __('info.question_yes') }}
                        <a href="{{ route('authClient.login') }}" class="signuplink">
                            {{ __('info.login') }}
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
</body>

</html>
