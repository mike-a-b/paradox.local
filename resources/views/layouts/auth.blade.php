<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/favicon/favicon.ico">
    <link rel="manifest" href="/favicon/site.webmanifest">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Paradoxx Auth') }}</title>

    <!-- Scripts -->        
    <!-- <script src="{{asset('assets/js/public/auth.js')}}" defer></script> -->

    <!-- Styles -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">        
</head>
<body>    
    <div class="auth__wrapper">                
        @yield('content')        
    </div>
</body>
</html>
