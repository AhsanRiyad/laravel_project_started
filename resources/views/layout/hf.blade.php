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

    <div class="container-fluid bg-light">
      <div class="row">
        <div class="col">
          <div class="container">
            <div class="row">
              <div class="col-lg-2 col-12 justify-content-lg-start d-flex justify-content-center"><a href="{{ route('index') }}"><img src="{{ asset($logo) }}" ></a>
              </div>

              <div class="col-lg-6 col-12 align-self-lg-center">

                <form action="{{ route('product.searchProducts') }}" method="GET">



                  <div class="form-row align-items-center">
                    <div class="col-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text"><i class="fas fa-search"></i>


                          </div>
                        </div>

                        
                        <p hidden="true" id="autosearchUrl">{{ route('productController.autosearch') }}</p>

                        <input type="text" class="form-control" id="autosearch" placeholder="Search" name="searchbox">


                        <div class="input-group mt-2">
                          <div class="input-group-prepend">
                            <label class="input-group-text" for="idcategory">Categories</label>
                          </div>
                          <select class="custom-select" id="idcategory" name="catValue">
                            <option selected value="all">All</option>
                            <option value="monitor">Monitor</option>
                            <option value="hdd">Hard Disk</option>
                            <option value="motherboard">Motherboard</option>
                            <option value="ram">Ram</option>
                            <option value="processor">Processor</option>
                            <option value="printer">Printer</option>
                          </select>
                        </div>

                      </div>
                    </div>

                    <div class="col-2">
                      <button type="submit" class="btn btn-primary py-4 " name="submit">Search</button>



                    </div>
                  </div>



                </form> 




              </div>

             <div class="col-lg-1 px-0  col-3 offset-1 mt-3 mb-3 mb-lg-0 mt-lg-0 offset-lg-0 d-flex  align-self-lg-center">

                @if($loginStatus == false)

                <a href="{{ route($signup) }}" class="btn btn-success ">Sign Up </span>
                </a> 
          
                
              @elseif($loginStatus==true)

              <a href="{{ route('product.cart') }}" class="btn btn-warning ">Cart<span class="badge badge-light" id="cart_count"> {{$cart_count}} </span>
              </a> 

              
              @endif



          </div>

          <div style="margin-left: -10px;" class=" col-lg-1 px-0  col-3 offset-1 mt-3 mb-3 mb-lg-0 mt-lg-0 offset-lg-0 d-flex  align-self-lg-center">





           @if($loginStatus==false)

            <a href="http://localhost:3000/product/cart" class="btn btn-warning ">Support</span>
            </a> 

           @elseif($loginStatus==true)

          <a href="{{ route('dashboardController.dashboard') }}" class="btn btn-info">Dashboard</a>


         @endif


      </div>






      <div style="margin-left: 2px;" class=" col-lg-1 px-0  col-3 offset-1 mt-3 mb-3 mb-lg-0 mt-lg-0 offset-lg-0 d-flex  align-self-lg-center">







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
      </div>
    </div>


    <div class="row bg-light border">
      <div class="col-6 offset-3">
        <nav class="navbar navbar-expand-lg navbar-light ">


          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">


             <a class="navbar-brand text-danger" href="#">Categories</a>
             <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Monitor
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['monitor' , 'lg']) }}">LG</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['monitor' , 'samsung']) }}">Samsung</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['monitor' , 'walton']) }}">Walton</a>
              </div>
            </li>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Hard Disk
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('product.categorySearch' , ['hdd' , 'toshiba']) }}">Toshiba</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['hdd' , 'western_digital']) }}">Western Digital</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['hdd' , 'adata']) }} ">Adata</a>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Printer
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['printer' , 'canon']) }}">Canon</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['printer' , 'hp']) }}">HP</a>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                RAM
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['ram' , 'transcend']) }} ">Transcend</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['ram' , 'adata']) }}  ">Adata</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['ram' , 'razor']) }}">Razor</a>

              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Motherboard
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['motherboard' , 'gigabyte']) }}">GigaByte</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['motherboard' , 'asus']) }} ">Asus</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['motherboard' , 'intel']) }} ">Intel</a>

              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Processor
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['processor' , 'intel']) }}">Intel</a>
                <a class="dropdown-item" href=" {{ route('product.categorySearch' , ['processor' , 'amd']) }} ">AMD</a>


              </div>
            </li>


          </ul>

        </div>
      </nav>
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
