<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- csrf_token for vue js -->
        <meta name="csrf-token" content="{{ csrf_token() }}" >
        <script> window.Laravel = { csrfToken : '{{ csrf_token() }}' } </script>

        <title>First page</title>

        <!-- boostrap -->
        <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('/bootstrap-reboot.css') }}">
        <link rel="stylesheet" href="{{ asset('/bootstrap-grid.css') }}">
        
        <!-- jquery ui -->
        <link rel="stylesheet" href="{{ asset('/jquery-ui.css') }}">
        <link rel="stylesheet" href="{{ asset('/jquery-ui.structure.css') }}">
        <link rel="stylesheet" href="{{ asset('/jquery-ui.theme.css') }}">
        
        <!-- stylesheet -->
        <link rel="stylesheet" href="{{ asset('/css/style.css') }}">


        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
        <body>
        
        @yield('content');



       
        



        <!-- vue js entry point -->
        <script src="{{ asset('js/app.js') }}" ></script>
        
        <!-- jquery -->
        <script src="{{ asset('js/jquery-3.3.1.js') }}" ></script>
        
        <!-- bootstrap -->
        <script src="{{ asset('js/bootstrap.js') }}" ></script>
        <script src="{{ asset('js/bootstrap.bundle.js') }}" ></script>
        
        <!-- jquery ui -->
        <script src="{{ asset('js/jquery-ui.js') }}" ></script>
        
        <!-- main js -->
        <script src="{{ asset('js/main.js') }}" ></script>


            
    </body>
</html>
