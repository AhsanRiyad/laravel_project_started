<!-- variables -->
@php

$carouselImg1 = 'img/product1.jpg' ; 
$carouselImg2 = 'img/product2.jpg' ; 
$carouselImg3 = 'img/product3.jpg' ; 
$productImg = 'img/cat1.jpg';

@endphp



@extends('layout.hf')


@section('content')
<!-- carousel starts -->
<div class="container-fluid">
	<div class="row admin_background">
		<div class="col">
			<div class="container">
				<div class="row">
					<div class="col">
						<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
							<div class="carousel-inner">
								<div class="carousel-item active">
									<img class="d-block w-100" src="{{ asset($carouselImg1) }}" alt="First slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset($carouselImg2) }}"  alt="Second slide">
								</div>
								<div class="carousel-item">
									<img class="d-block w-100" src="{{ asset($carouselImg3) }}"  alt="Third slide">
								</div>
							</div>
							<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- carousel ends -->



<!--New product category added -->
<div class="container-fluid">
	<div class="row bg-light">
		<div class="col">
			<div class="container">
				<div class="row pt-5">
					<a class="text-dark" href="#"><h3>Featured Products</h3></a>
					<div class="w-100"></div>


					
					@php
					$i = 0;
					@endphp
					@foreach ($products as $product)
					


					<div class="col-xl-2 col-6  mt-xl-2 mt-3" >
						
						<a style="color: black;" href="{{ route('product.details' , [$product->product_id]) }}">
							<div class="w_p bg-white ">
								<img class="img-fluid" src="{{ asset($product->image) }}" alt="">

								<div class="w-100 pl-2">
									<h6> {{ $product->product_name }} </h6>
									<h6 class="text-danger">{{ $product->product_price }}</h6>
									<p class="text-danger mb-0"> 
										<strike class="text-muted">

											{{ $product->product_original_price }}
										</strike>
										<small class="text-muted"> 
										{{ $product->category_id }}
										</small>
									</p>
									<div class="w-100 mt-0 pb-3">

										<span  class="fa fa-star  @if(  $product->rating  >= 1 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $product->rating  >= 2 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $product->rating  >= 3 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $product->rating  >= 4 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star @if(  $product->rating  >= 5 )
												text-warning


										@endif "></span>

									</div>


								</div>

							</div>

						</a>

						
					</div>



					@php
					$i++;
					@endphp

					@break($i==12)


					@endforeach



				</div>
			</div>
		</div>
	</div>
</div>


<!-- just in products ends -->





<!-- just in products starts -->

<!--New product category added -->
<div class="container-fluid">
	<div class="row bg-light">
		<div class="col">
			<div class="container">
				<div class="row pt-5">
					<a class="text-dark" href="#"><h3>Recommended for you</h3></a>
					<div class="w-100"></div>


					
					@php
					$i = 0;
					@endphp
					@foreach ($recommendProducts as $rec)
					


					<div class="col-xl-2 col-6  mt-xl-2 mt-3" >
						
						<a style="color: black;" href="{{ route('product.details' , [$rec->product_id]) }}">
							<div class="w_p bg-white ">
								<img class="img-fluid" src="{{ asset($product->image) }}" alt="">

								<div class="w-100 pl-2">
									<h6> {{ $rec->product_name }} </h6>
									<h6 class="text-danger">{{ $rec->product_price }}</h6>
									<p class="text-danger mb-0"> 
										<strike class="text-muted">

											{{ $rec->product_original_price }}
										</strike>
										<small class="text-muted"> 
										{{ $rec->category_id }}
										</small>
									</p>
									<div class="w-100 mt-0 pb-3">

										<span  class="fa fa-star  @if(  $rec->rating  >= 1 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $rec->rating  >= 2 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $rec->rating  >= 3 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $rec->rating  >= 4 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star @if(  $rec->rating  >= 5 )
												text-warning


										@endif "></span>

									</div>


								</div>

							</div>

						</a>

						
					</div>



					@php
					$i++;
					@endphp

					@break($i==12)


					@endforeach



				</div>
			</div>
		</div>
	</div>
</div>



<!-- just in products ends -->

<!--Recommended product category added -->


<!-- load more button -->
<a href="{{ route('productController.all_products') }}"><div class="col-12 d-flex justify-content-center mb-2 mt-4">
		<button class="w-25 bg-transparent py-2 border border-info text-info btn rounded-0">
			LOAD MORE
		</button>				
	</div></a>
<div class="w-100 mb-3">
	<hr>
</div>


@endsection

