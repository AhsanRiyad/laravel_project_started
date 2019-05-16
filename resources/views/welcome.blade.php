<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vue js</title>
        

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        
    </head>
    <body>
        
        <div id="app">
            <h1>
                hellow @{{ name }}
            </h1>
            
        </div>


        <script src="{{ asset('js/app.js') }}"></script>


        
    </body>
</html>
