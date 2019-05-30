<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Vue js</title>
        

        <link rel="stylesheet" href="<?php echo e(mix('/css/app.css')); ?>">
        
    </head>
    <body>
        
        <div id="app">

            
            <div class="container">
                
                <navbar v-on:add_meal="add_meal($event)"></navbar>
            
                <add_meal v-if="status"></add_meal>
            </div>
                
            <router-view></router-view>

        </div>





        <script src="<?php echo e(mix('js/app.js')); ?>"></script>


        
    </body>
</html>
<?php /**PATH C:\Users\Riyad\Desktop\New folder\laravel_project_started\resources\views/welcome.blade.php ENDPATH**/ ?>