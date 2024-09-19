<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $segment = request()->segment(1);
@endphp

@foreach (__('sidebar.module') as $key => $val)
    @if (in_array($segment, $val['name']))
        <title>{{ $val['title'] }}</title>
    @break
@endif
@endforeach

<link rel="icon" type="image/png" href="/thuongmaidientu/public/userfiles/image/temp/bug.png">

<base href="{{ config('app.url') }}">
<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('backend/plugins/jquery-ui.css') }}" rel="stylesheet">

@if (isset($config['css']) && is_array($config['css']))
@foreach ($config['css'] as $val)
    <link href="{{ asset($val) }}" rel="stylesheet">
@endforeach
@endif

<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/customize.css') }}" rel="stylesheet">
<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>
<script>
    var BASE_URL = "{{ config('app.url') }}"
    var SUFFIX = "{{ config('apps.general.suffix') }}"
    var doNotSeoDescription = "{{ __('form.do_not_seo_description') }}"
    var doNotSeoTitle = "{{ __('form.do_not_seo_title') }}"
    var yourPathUrl = "{{ __('form.your_path_url') }}"
    var unitCharacter = "{{ __('unit.characters') }}"
</script>
