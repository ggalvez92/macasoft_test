<!DOCTYPE html>
<html lang="es">
    <head>
        <title>MACASOFT TEST - GIANPIERRE GÁLVEZ ARÁMBULO</title>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico?'.time()) }}" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('backend/css/app.css?'.time()) }}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </head>
<body>
    <main id="app">
        <loading></loading>
        @yield('content')
        
    </main>
    @include('backend.footer')
    <script src="{{ asset('backend/js/app.js?'.time()) }}"></script>
</body>
</html>