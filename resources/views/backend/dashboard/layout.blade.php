<!DOCTYPE html>
<html>

<head>
    @include('backend.dashboard.component.head')
</head>

<body>
    @include('component.loadingSpinner')
    <div id="wrapper">
        @include('backend.dashboard.component.sidebar')

        <div id="page-wrapper" class="gray-bg">
            @include('backend.dashboard.component.nav')
            @include($template)
            @include('backend.dashboard.component.footer')
        </div>
    </div>

    @include('backend.dashboard.component.script')
</body>

</html>
