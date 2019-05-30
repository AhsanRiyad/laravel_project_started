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

            
            <!-- <div class="container">
                
                <navbar v-on:add_meal="add_meal($event)"></navbar>
                
                <HelloWorld></HelloWorld>
            
                <add_meal v-if="status"></add_meal>
            </div>
                
            <router-view></router-view> -->


            <v-app>
      <v-content>
        <v-container>Hello world</v-container>

        <v-btn color="info">Info</v-btn>
      </v-content>
    
    <hellow></hellow>


    </v-app>

        </div>





        <script src="{{ mix('js/app.js') }}"></script>


        
    </body>
</html>
