@php
$productImg = 'img/cat1.jpg';
$postReview = "product.postReview";
$loginStatus = $loginStatus;
@endphp

@extends('layout.hf')

@section('content')



<!--New product category added -->

<div class="container-fluid">
	<div class="row bg-light">
		<div class="col">
			<div class="container">
				<div class="row pt-5">
					<a class="text-dark" href="#"><h3>Featured Products</h3></a>
					<div class="w-100"></div>

					@if( $products == [] )
					
					<h1 class="text-danger">Search keyword does not match!!!</h1>
					
					@endif
					
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



					


					@endforeach



				</div>
			</div>
		</div>
	</div>
</div>


<!-- just in products ends -->



@endsection