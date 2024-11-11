<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

@php
    $currentRoute = request()->route()->getName(); // Lấy route hiện tại
    $pageTitle = null; // Biến để lưu tiêu đề hiển thị

    // Lặp qua các module để tìm tiêu đề
    foreach (__('sidebar.module') as $module) {
        if (isset($module['subModule'])) {
            foreach ($module['subModule'] as $subModule) {
                if ($subModule['route'] === $currentRoute) {
                    $pageTitle = $subModule['title']; // Gán tiêu đề từ subModule nếu route khớp
                    break 2; // Thoát khỏi cả 2 vòng lặp
                }
            }
        }
    }

    // Nếu không khớp với subModule, kiểm tra tiêu đề của module
    if (!$pageTitle) {
        foreach (__('sidebar.module') as $module) {
            if (in_array(request()->segment(1), $module['name'])) {
                $pageTitle = $module['title']; // Gán tiêu đề từ module
                break;
            }
        }
    }
@endphp

<title>{{ $pageTitle ?? 'TC Shop Admin' }}</title>

<link rel="icon" type="image/png" href="{{ $system['homepage_favicon'] }}">

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
    var apply = "{{ __('form.apply') }}"
    var cancel = "{{ __('form.cancel') }}"
    var edit = "{{ __('form.edit') }}"
    var save = "{{ __('button.save') }}"
</script>
