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

            
            
            <div class="container">
                
                <navbar v-on:add_meal="add_meal($event)"></navbar>

                <add_meal v-if="status"></add_meal>
            </div>

            

        </div>





        <script src="{{ asset('js/app.js') }}"></script>


        
    </body>
</html>
