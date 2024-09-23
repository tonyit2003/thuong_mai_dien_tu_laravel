<base href="{{ config('app.url') }}">
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,follow">
<meta name="author" content="">
<meta name="copyright" content="">
<meta http-equiv="refresh" content="1800">
<link rel="icon" href="" type="image/png" sizes="30x30">
{{-- GOOGLE --}}
<title>TC Shop</title>
<meta name="description" content="">
<link rel="canonical" href="">
<meta property="og:locale" content="vi_VN">
{{-- FACEBOOK --}}
<meta property="og:title" content="">
<meta property="og:type" content="article">
<meta property="og:image" content="">
<meta property="og:url" content="">
<meta property="og:description" content="">
<meta property="og:site_name" content="">
<meta property="og:admins" content="">
<meta property="og:app_id" content="">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:image" content="">

<link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}">
@php
    $coreCss = [
        'frontend/resources/fonts/font-awesome-4.7.0/css/font-awesome.min.css',
        'frontend/resources/uikit/css/uikit.modify.css',
        'https://unpkg.com/swiper/swiper-bundle.min.css',
        'frontend/resources/library/css/library.css',
        'frontend/resources/plugins/wow/css/libs/animate.css',
        'frontend/resources/style.css',
        'frontend/resources/library/js/jquery.js',
    ];
@endphp
@foreach ($coreCss as $item)
    <link rel="stylesheet" href="{{ asset($item) }}">
@endforeach
