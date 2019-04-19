@php
$logoSrc = "img/logo_dashboard.png";
$addPromo = "http://localhost:3000/product/addpromo";
$viewPromo = "http://localhost:3000/product/viewpromo";
$signout = "authenticationController.logout";
$homepage = "index";
$u_type = $userinfo[0]['u_type'];

@endphp


<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- csrf_token for vue js -->
  <meta name="csrf-token" content="{{ csrf_token() }}" >
  <script> window.Laravel = { csrfToken : '{{ csrf_token() }}' } </script>

  <title>First page</title>

  <link rel="icon" href="{{ asset('img/fevicon.png') }}" type="image/gif" sizes="16x16">


  <!-- jquery ui -->
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.structure.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.theme.css') }}">

  <!-- boostrap -->
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.css') }}">



  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  
  


  <!-- stylesheet -->
  <link rel="stylesheet" href="{{ asset('/css/style.css') }}">


  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

  @yield('stylesheet')


  <body>
  
  
<p hidden id="getUrl">{{ asset('') }}</p>

  @yield('content')





<!-- navigation bar and search bar starts -->
  <!-- fixed horizontal -->
  <div class="admin_navbar_horizontal bg-secondary justify-content-center d-flex align-items-center">
    <p class="text-white h3">Good Morning, {{ $userinfo[0]['last_name'] }}

      
    
     </p>
  </div>



  <!-- fixed vertical -->
  <div class=" admin_navbar_veritcal bg-dark ">
    <div class=" bg-light d-flex justify-content-center">
      <a href=><img class=" py-1" src="{{ asset( $logoSrc ) }}" alt=""></a>

    </div>

    
    @if($u_type == 'admin')
    

    
      <a class="text-white" href="{{ route('a_pos.index') }}">
      <div class="      
        py-2 text-center my-4 bg-secondary">

        <i class="fas w_f fa-tachometer-alt"></i> Point Of Sale

      </div></a>

      <a class="text-white" href="{{ route('userController.addUser') }}">
        <div class=" bg-secondary py-2 text-center my-4">

          <i class="fas w_f fa-chart-line"></i> Add Customer

        </div></a>

      

        <a class="text-light" href='{{ route("userController.add_factory") }}'>
          <div class="
          py-2 text-center my-4 bg-secondary">

          <i class="fas w_f fa-user "></i> Add Factory

        </div></a>

        <a class="text-light" href='{{ route("userController.ship_req_india") }}'>
          <div class="  
          py-2 text-center my-4 bg-secondary">

          <i class="fas fa-shopping-cart"></i> Shipment Request <span class="badge badge-primary p-2 text-white " id="reqCount">0</span>

        </div></a> 


        <a class="text-light" href='{{ route("userController.ship_req_bd") }}'>
          <div class="  
          py-2 text-center my-4 bg-secondary">

          <i class="fas fa-shopping-cart"></i> Request Shipment BD 

        </div></a>


        <a class="text-white" href="{{ route('userController.add_raw_materials') }}">
          <div class="  bg-secondary py-2 text-center my-4">

            <i class="fas w_f fa-envelope"></i> Raw Materials

          </div></a>
        
       <a class="text-white" href='{{ route("accountController.money_transfer") }}'>
          <div class="bg-secondary py-2 text-center my-4">

            <i class="fas fa-shopping-cart"></i> Money Transfer

          </div>

        </a>


          <a class="text-white" href="http://localhost:3000/product/viewproducts">
            <div class="  bg-secondary py-2 text-center my-4">

              <i class="fab fa-product-hunt"></i> Reports

            </div></a>



            <a class="text-white" href="  {{ route($signout) }}  ">
              <div class="  bg-danger py-2 text-center my-4">

                <i class="fas fa-sign-out-alt"></i>Sign Out

              </div></a>


      


    @endif



    @if($u_type == 'user')
  <!-- <a class="text-light" href='http://localhost:3000/dashboard/profile'>
          <div class="
          py-2 text-center my-4 bg-secondary">
  
          <i class="fas fa-shopping-cart "></i> My Orders
  
        </div></a> -->

    <a class="text-light" href='http://localhost:3000/dashboard/profile'>
          <div class="
          py-2 text-center my-4 bg-secondary">

          <i class="fas w_f fa-user "></i> Profile

        </div></a>
    


    <a class="text-white" href='<%=homepage%>'>
          <div class="bg-success py-2 text-center my-4">

            <i class="fas fa-shopping-cart"></i> Back to Shop Page

          </div></a>

            <a class="text-white" href='<%=signout%>'>
              <div class="  bg-danger py-2 text-center my-4">

                <i class="fas fa-sign-out-alt"></i>Sign Out

              </div></a>






    @endif

    

            </div>



<!-- jquery -->
    <script src="{{ asset('js/jquery-3.3.1.js') }}" ></script>

    <!-- jquery ui -->
    <script src="{{ asset('js/jquery-ui.js') }}" ></script>

    <!-- bootstrap -->
    <script src="{{ asset('js/bootstrap.js') }}" ></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}" ></script>




    <!-- main js -->
    <script src="{{ asset('js/main.js') }}" ></script>

   @yield('script')
    

  </body>