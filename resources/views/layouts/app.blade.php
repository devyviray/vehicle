<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Centralized Vehicle Portal') }}</title>
        <!-- Favicon -->
        {{-- <link href="{{ asset('argon') }}/img/brand/favicon.png" rel="icon" type="image/png"> --}}
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

        <!-- Styles -->
        <link href="{{ asset('css/all.css') }}" rel="stylesheet">
        <!-- Argon CSS -->
        {{-- <link type="text/css" href="{{ asset('css/argon.min.css?v=1.0.0') }}" rel="stylesheet"> --}}
    </head>
    <body class="{{ $class ?? '' }}">
        <div id="app">
            @auth()
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth
        
            <div class="main-content">
                @include('layouts.navbars.navbar')
                @yield('content')
            </div>

            @guest()
            
            @endguest
        </div>
        
        @stack('js')
        
        <!-- Scripts -->
        <script src="{{ asset('js/all.js') }}" defer></script>
        <!-- Argon JS -->
        {{-- <script src="{{ asset('js/argon.min.js?v=1.0.0') }}"></script> --}}
    </body>
</html>