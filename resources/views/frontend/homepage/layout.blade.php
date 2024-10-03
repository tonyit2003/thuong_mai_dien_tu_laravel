<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.component.head')
</head>

<body>
    @include('frontend.component.header')
    @yield('content')
    @include('frontend.component.footer')
    @include('frontend.component.script')
    @include('frontend.component.popup')
</body>

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

</html>
