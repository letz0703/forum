<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.App = {!! json_encode([
            'signedIn'=> auth()->check(),
            'user' => auth()->user()
        ])!!}

    </script>
@yield('scripts')

<!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.1.0/trix.css" rel="stylesheet">
    @yield('header')
    <style>
        body {
            padding-bottom: 100px;
        }

        .level {
            display: flex;
            align-items: center;
            /*align-items: flex-start;*/
        }

        .level-item {
            margin-right: 1em;
        }

        .ml-a {
            margin-left: auto;
        }

        .flex {
            flex: 1
        }

        .ais-highlight > em {
            backgroud: yellow;
            font-style: normal
        }

        [v-cloak] {
            display: none;
        }
    </style>
</head>
<body style="padding-bottom: 100px">
<div id="app">

    @include('layouts.nav')

    <main class="py-4">
        @yield('content')
    </main>
    {{--<flash message="Temporary Message"></flash>--}}
    <flash message="{{ session('flash') }}"></flash>
    {{--    <flash message="{{ session('flash') }}"></flash>--}}
</div>
</body>
</html>
