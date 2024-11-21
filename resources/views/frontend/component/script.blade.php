@php
    $coreScript = [
        'frontend/resources/library/js/jquery.js',
        'backend\js\plugins\toastr\toastr.min.js',
        'backend\plugins\jquery-ui.js',
        'frontend/resources/plugins/wow/dist/wow.min.js',
        'frontend/resources/uikit/js/uikit.min.js',
        'frontend/resources/uikit/js/components/sticky.min.js',
        'frontend/core/plugins/jquery-nice-select-1.1.0/js/jquery.nice-select.min.js',
        'frontend/resources/function.js',
    ];
    if (isset($config['js'])) {
        foreach ($config['js'] as $val) {
            array_push($coreScript, $val);
        }
    }
@endphp

@foreach ($coreScript as $item)
    <script src="{{ asset($item) }}"></script>
@endforeach

<script src="https://unpkg.com/swiper@11.1.15/swiper-bundle.min.js"></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
    src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0&appId=103609027035330&autoLogAppEvents=1" nonce="E1aWx0Pa"></script>

<script>
    (function(d, t) {
        var BASE_URL = "https://app.chatwoot.com";
        var g = d.createElement(t),
            s = d.getElementsByTagName(t)[0];
        g.src = BASE_URL + "/packs/js/sdk.js";
        g.defer = true;
        g.async = true;
        s.parentNode.insertBefore(g, s);
        g.onload = function() {
            window.chatwootSDK.run({
                websiteToken: 'ihSoP3cwMqKnYYctnJsm1Hg9',
                baseUrl: BASE_URL
            })
        }
    })(document, "script");
</script>
