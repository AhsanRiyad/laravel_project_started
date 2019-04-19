@php
$homepage = "http://localhost:3000/";
$logo = 'img/logo.png' ; 
$signup = 'authentication.signup';
$signout = "authenticationController.logout";

@endphp

<!-- $loginStatus = false ;  -->


<p hidden id="getUrl">{{ asset('') }}</p>


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

  <body>

    <div class="container bg-light py-5">
      <div class="row">
          

          <div class="col-2 offset-3">
             
                @if($loginStatus == false)
                <a href="{{ route($signup) }}" class="btn btn-success ">Sign Up </span>
                </a>                
      

              
              @endif

          </div>



          <div class="col-2 ">

           @if($loginStatus==false)
          
            <a href="{{ route('a_pos.index') }}" class="btn btn-info">Support</a>

           @elseif($loginStatus==true)

          <a href="{{ route('a_pos.index') }}" class="btn btn-info">Dashboard</a>
         @endif

      </div>




      <div class=" col-2 ">

        @if($loginStatus==false)
        <a href="{{ route('authentication.login') }}" class="btn btn-danger ">
          Sign In

        </a>

        @elseif($loginStatus==true)

     <a href="{{ route($signout) }}" class="btn btn-danger">
        SignOut
      </a>
 @endif
  </div>
           
    </div>
    </div>
    </div>  


  @yield('content');




















  <!-- footer part starts -->

  <div class="container-fluid">
    <div class="row bg-dark">
      <div class="col">
        <div class="container text-white">
          <div class="row">
            <div class="col-md-3 col-12 text-center text-sm-left pt-lg-5 pt-5">

              <h5>Get to know us</h5>
              <a class="text-white" href="">About Us</a><br>
              <a class="text-white" href="">Privacy Policy</a><br>
              <a class="text-white" href="">Cookie Policy</a><br>
              <a class="text-white" href="">Warranty Policy</a><br>

            </div>
            <div class="col-md-3 col-12 text-center text-sm-left pt-lg-5 pt-5">

              <h5>Let Us Help You</h5>
              <a class="text-white" href="">Your account</a><br>
              <a class="text-white" href="">Your order</a><br>
              <a class="text-white" href="">Track your order</a><br>
              <a class="text-white" href="">How to place an order</a><br></div>
              <div class="col-md-3 col-12 text-center text-sm-left pt-lg-5 pt-5">

                <h5>Get in touch</h5>
                <a class="text-white" href="">Contact Us</a><br>
                <a class="text-white" href="">Our Blogs</a><br>
                <a class="text-white" href="">Track your order</a><br>
                <a class="text-white" href="">How to place an order</a><br>

              </div>
              <div class="col-md-3  col-12 text-center text-sm-left pt-lg-5 pt-5 pb-5">

                <h5>UMART&trade;</h5>
                <h6>House:84</h6>
                <h6>Road: 04</h6>
                <h6>Block: B</h6>
                <h6>Bashundhara R/A</h6>
                <h6>Dhaka-1216</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer part ends -->


    <!-- copy right part starts -->
    <div class="container-fluid">
      <div class="row bg-secondary pb-3 pt-3">
        <div class="col">
          <div class="container">
            <div class="row text-center text-lg-left">
              <div class="col"><i class="far fa-copyright text-white"></i><a class="text-white" href="http://fiver.com/riyad_ahsan"> 2018 Riyad Ahsan <abbr title="Web Developer"> All Rights Reserved</abbr></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- copy right part ends -->






















    <!-- vue js entry point -->
    <!-- <script src="{{ asset('js/app.js') }}" ></script> -->

    <!-- jquery -->
    <script src="{{ asset('js/jquery-3.3.1.js') }}" ></script>

    <!-- jquery ui -->
    <script src="{{ asset('js/jquery-ui.js') }}" ></script>

    <!-- bootstrap -->
    <script src="{{ asset('js/bootstrap.js') }}" ></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}" ></script>



    <!-- main js -->
    <script src="{{ asset('js/main.js') }}" ></script>



  </body>











  </html>
