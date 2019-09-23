@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection


<p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>
<p hidden="true" id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>


  @section('content')

<div class="container-fluid bg-light mt-0 position-fixed">
	<div class="row ">
		<div class="col-12 admin_margin ">
			<p class="h2 text-dark ">Admin Dashboard</p>
			<nav aria-label="breadcrumb ">
				<ol class="breadcrumb bg-light mt-0 pt-0 pl-0">
					<li class="breadcrumb-item "><a class="text-danger" href="admin_home.php">Home</a></li>
					<li class="breadcrumb-item "><a class="text-muted" href="admin_home.php">Dashboard</a></li>
				</ol>
			</nav>



			<!-- admin box started -->
			<div class="row">
				<div class="col-2 text-white order-0"> 

					<div class=" float-left box1  border border-light border-top-0 border-bottom-0 border-left-0 border-right d-flex align-items-center justify-content-center bg-danger">

						<i class="fas fa-sync h1"></i>

					</div>

					<div class="d-inline float-left">
						<div class=" d-block box2 bg-danger border border-light border-top-0 border-left-0 border-right-0 border-bottom d-flex  justify-content-center">


							<p class="d-flex align-items-center h5 my-0 h">{{ $revenue }} </p>

						</div>

						<div class="d-block box2 bg-danger d-flex align-items-center justify-content-center">


							REVENUE

						</div>

					</div>




				</div>


				<div class="col-2 text-white order-2"> 

					<div class=" float-left box1 bg-success border border-light border-top-0 border-bottom-0 border-left-0 border-right d-flex align-items-center justify-content-center">

						<i class="fas fa-cart-plus h1"></i>

					</div>

					<div class="d-inline float-left">
						<div class=" d-block box2 bg-success border border-light border-top-0 border-left-0 border-right-0 border-bottom d-flex  justify-content-center">


							<p class="d-flex align-items-center h5 my-0">{{ $order }}</p>

						</div>

						<div class=" d-block box2 bg-success d-flex align-items-center justify-content-center">


							ORDER

						</div>

					</div>




				</div>



				<div class="col-2 text-white order-3"> 

					<div class=" float-left box1 bg-info border border-light border-top-0 border-bottom-0 border-left-0 border-right d-flex align-items-center justify-content-center">

						<i class="fas fa-chart-bar h1"></i>

					</div>

					<div class="d-inline float-left">
						<div class=" d-block box2 bg-info border border-light border-top-0 border-left-0 border-right-0 border-bottom d-flex  justify-content-center">


							<p class="d-flex align-items-center h5 my-0">{{ $products }}</p>

						</div>

						<div class=" d-block box2 bg-info d-flex align-items-center justify-content-center">


							PRODUCTS

						</div>

					</div>




				</div>



				<div class="col-2 text-white order-1"> 

					<div class=" float-left box1 bg-warning border border-light border-top-0 border-bottom-0 border-left-0 border-right d-flex align-items-center justify-content-center">

						<i class="fas fa-users h1"></i>

					</div>

					<div class="d-inline float-left">
						<div class=" d-block box2 bg-warning border border-light border-top-0 border-left-0 border-right-0 border-bottom d-flex  justify-content-center">


							<p class="d-flex align-items-center h5 my-0">{{ $visit }}</p>

						</div>

						<div class=" d-block box2 bg-warning d-flex align-items-center justify-content-center">


							VISITS

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection

