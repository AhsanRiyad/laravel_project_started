<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Vue js</title>


    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

</head>
<body>

    <div id="app">
        
        <App></App>
          
            
    </div> 



            <!-- <div class="container">
                
                <navbar v-on:add_meal="add_meal($event)"></navbar>
                
                <HelloWorld></HelloWorld>
            
                <add_meal v-if="status"></add_meal>
            </div>
                
            <router-view></router-view> -->


           <!--  <v-app>
             <v-content>
           
               <navbar v-on:add_meal="add_meal($event)"></navbar>
               <v-container>Hello world</v-container>
           
               <v-btn color="info">Info</v-btn>
           </v-content>
           
           <hellow></hellow>
           
           <router-view></router-view>
                   </v-app> -->

   



    

    <script src="{{ mix('js/app.js') }}"></script>

    <style>
      
    .addM{
      margin-top: 400px;
    }

    </style>    

</body>
</html>
