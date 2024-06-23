<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tony Shop</title>

    <link href="backend/css/bootstrap.min.css" rel="stylesheet">
    <link href="backend/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="backend/css/animate.css" rel="stylesheet">
    <link href="backend/css/style.css" rel="stylesheet">
    <link href="backend/css/customize.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Welcome to Tony Shop</h2>

                <p>
                    Chào mừng bạn đến với Tony Shop, nơi hội tụ những sản phẩm chất lượng và dịch vụ tuyệt vời!
                </p>

                <p>
                    Những nỗ lực của bạn trong việc tuyển chọn sản phẩm và cung cấp dịch vụ tuyệt vời đã tạo nên một
                    thương hiệu uy tín và đáng tin cậy.
                </p>

                <p>
                    Cảm ơn bạn đã mang đến cho khách hàng một trải nghiệm mua sắm thật tuyệt vời.
                </p>

                <p>
                    Chúc bạn và Tony Shop ngày càng phát triển và thành công hơn nữa!
                </p>

                <p>
                    <small>Hãy để Tony Shop trở thành người bạn đồng hành đáng tin cậy của các khách hàng trong hành
                        trình tìm kiếm phong cách riêng và sự tiện nghi trong cuộc sống!</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">

                    {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif --}}

                    <form method="post" class="m-t" role="form" action="{{ route('auth.login') }}">
                        @csrf

                        <div class="form-group">
                            <input name="email" type="text" class="form-control" placeholder="Email"
                                value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="error-message">* {{ $errors->first('email') }} </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <input name="password" type="password" class="form-control" placeholder="Mật khẩu">
                            @if ($errors->has('password'))
                                <span class="error-message">* {{ $errors->first('password') }} </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary block full-width m-b">Đăng nhập</button>

                        <a href="#">
                            <small>Quên mật khẩu?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>Bản quyền thuộc về Tony &copy; 2024</small>
                    </p>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">
                Tony Shop
            </div>
            <div class="col-md-6 text-right">
                <small>© 2024-2030</small>
            </div>
        </div>
    </div>

</body>

</html>
