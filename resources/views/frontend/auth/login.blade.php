<!--
Author: W3layouts
Author URL: http://w3layouts.com
-->
<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Đăng nhập</title>

    <!-- Meta tags -->
    {{-- <link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}"> --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Style -->
    <link rel="stylesheet" href="frontend/auth/css/style.css" type="text/css" media="all" />
</head>

<body>
    <!-- login form -->
    <section class="w3l-login">
        <div class="overlay">
            <div class="wrapper">
                <div class="logo">
                    <a class="brand-logo" href="index.html">TC - SHOP</a>
                </div>
                <div class="form-section">
                    <h3>Đăng nhập</h3>
                    <h6>Để tiếp tục với hệ thống</h6>
                    <form action="{{ route('authClient.login') }}" method="post" class="signin-form">
                        @csrf
                        <div class="form-input">
                            <input type="email" name="email" placeholder="Email" required="" autofocus value="caotancong2003@gmail.com">
                        </div>
                        <div class="form-input">
                            <input type="password" name="password" placeholder="Mật khẩu" required="" value="123456">
                        </div>
                        <label class="check-remaind">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            <p class="remember">Lưu lại</p>
                        </label>
                        <button type="submit" class="btn btn-primary theme-button mt-4">Đăng nhập</button>
                        <div class="new-signup">
                            <a href="#reload" class="signuplink">Quên mật khẩu?</a>
                        </div>
                    </form>
                    <p class="signup">Bạn chưa có tài khoản? <a href="#signup.html" class="signuplink">Đăng ký</a></p>
                </div>
            </div>
        </div>
        <div id='stars'></div>
        <div id='stars2'></div>
        <div id='stars3'></div>

        <!-- copyright -->
        <div class="copy-right">
            <p>&copy; 2020 Snow Login Form. All rights reserved | Design by <a href="http://w3layouts.com/" target="_blank">W3Layouts</a></p>
        </div>
        <!-- //copyright -->
    </section>

    <!-- /login form -->
</body>

</html>
