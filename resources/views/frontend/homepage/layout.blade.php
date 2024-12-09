<!DOCTYPE html>
<html lang="en">

<head>
    @include('frontend.component.head')
</head>

<body>
    @include('component.loadingSpinner')
    @include('frontend.component.header')
    @yield('content')
    @include('frontend.component.footer')
    @include('frontend.component.script')
    {{-- @include('frontend.component.popup') --}}
</body>

</html>
