<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin</title>

<base href="http://localhost/thuongmaidientu/public/">
<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">

@if (isset($config['css']) && is_array($config['css']))
    @foreach ($config['css'] as $val)
        <link href="{{ asset($val) }}" rel="stylesheet">
    @endforeach
@endif

<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/customize.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>
