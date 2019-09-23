<!-- variables -->
@php

$carouselImg1 = 'img/product1.jpg' ; 
$carouselImg2 = 'img/product2.jpg' ; 
$carouselImg3 = 'img/product3.jpg' ; 
$productImg = 'img/cat1.jpg';

@endphp



@extends('layout.hf')


@section('content')

<!--New product category added -->
<div class="container-fluid">
	<div class="row bg-light">
		<div class="col">
			<div class="container">
				<div class="row pt-5">
					<a class="text-dark" href="#"><h3>All Products </h3></a>
					<div class="w-100"></div>


					
					@foreach($searchResult as $s)


						<div class="col-xl-2 col-6  mt-xl-2 mt-3" >

							<a style="color: black;" href="{{ route('product.details' , [$s->product_id]) }}">
								<div class="w_p bg-white ">

									<img class="img-fluid" src=" {{ $s->image }} " alt="">

									<div class="w-100 pl-2">
										<h6> {{$s->product_name}} </h6>
										<h6 class="text-danger">{{$s->product_price}}</h6>
										<p class="text-danger mb-0"> 
											<strike class="text-muted">
												{{$s->product_original_price}}
												
											</strike>
											<small class="text-muted"> 
												{{$s->category_id}}
												
											</small>
										</p>
										<div class="w-100 mt-0 pb-3">

										<span  class="fa fa-star  @if(  $s->rating  >= 1 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $s->rating  >= 2 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $s->rating  >= 3 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star  @if(  $s->rating  >= 4 )
												text-warning


										@endif "></span>
										<span  class="fa fa-star @if(  $s->rating  >= 5 )
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

	<div class="row justify-content-md-center mt-3">
		<div class="col-auto ">
				{{ $searchResult->links() }}
		</div>
	</div>

</div>






<!-- just in products ends -->




@endsection
