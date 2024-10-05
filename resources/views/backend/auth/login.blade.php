<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TC Shop - Admin</title>
    <link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}">
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
                <h2 class="font-bold">Welcome to TC Shop</h2>
                <p>
                    {{ __('info.mess_1') }}
                </p>
                <p>
                    {{ __('info.mess_2') }}
                </p>
                <p>
                    {{ __('info.mess_3') }}
                </p>
                <p>
                    {{ __('info.mess_4') }}
                </p>
                <p>
                    {{ __('info.mess_5') }}
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
                            <label for="">Email</label>
                            <input name="email" type="text" class="form-control" placeholder="Email"
                                value="{{ old('email') ?? 'lehuutai090403@gmail.com' }}">
                            @if ($errors->has('email'))
                                <span class="error-message">* {{ $errors->first('email') }} </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('info.password') }}</label>
                            <input name="password" type="password" class="form-control" placeholder="{{ __('info.password') }}" value="123456">
                            @if ($errors->has('password'))
                                <span class="error-message">* {{ $errors->first('password') }} </span>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary block full-width m-b">{{ __('info.login') }}</button>

                        <a href="#">
                            <small>{{ __('info.forgot_password') }}</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <strong>Copyright</strong> {{ $system['homepage_copyright'] }}&copy; 2024-2030
                    </p>
                </div>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-6">
                TC Shop
            </div>
            <div class="col-md-6 text-right">
                <small>© 2024-2030</small>
            </div>
        </div>
    </div>

</body>

</html>
