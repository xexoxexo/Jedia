<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('img/logo/icon.png') }}" type="image/x-icon">
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    @stack('styles')
</head>
<body class="relative">
    @yield('modals')

    @yield('root')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/global.js') }}"></script>
    @stack('scripts')
</body>
</html>
