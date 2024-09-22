@php
    $coreScript = [
        'frontend/resources/library/js/jquery.js',
        'frontend/resources/plugins/wow/dist/wow.min.js',
        'frontend/resources/uikit/js/uikit.min.js',
        'frontend/resources/uikit/js/components/sticky.min.js',
        'frontend/resources/function.js',
    ];
@endphp
@foreach ($coreScript as $item)
    <script src="{{ asset($item) }}"></script>
@endforeach
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1"
    nonce="E1aWx0Pa"></script>
