@php

$productImg = 'img/product_demo.png';
$postReview = "product.postReview";
@endphp

@extends('layout.hf')


@section('content')

<div class="container">
	<div class="row justify-content-center">
		<div class="col-6">
			<img class="img-fluid" height="300" width="300" src="{{ asset($products[0]->image) }}" alt="">
			<hr>
			<h3>Name: {{ $products[0]->product_name }} </h3>
			<h4>Price: {{ $products[0]->product_price }} </h4>	
			<h4>Description: {{ $products[0]->descriptions }} </h4>	

			<b>Quantity:  </b>
			<select class="px-4" name="productQuantity" id="productQuantity">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
			</select>

			<div>


				@if($loginStatus==false)

				<button disabled class="btn btn-warning mt-2"> Please login to buy or Post a Review</button>

				@elseif($loginStatus==true)

			<input type="text" hidden="true"  value="{{ $pid }}" id="productId">
			
			<input type="text" hidden="true"  value="{{ $uid }}" id="user_id">



			<a onclick="" href="#" class="btn btn-warning mb-3 mt-2" id="addToCart">Add to Cart</a>

			<!-- <a onclick="jsFuntionAddToCart(this)" href="#" class="btn btn-success mb-3 mt-2" >Buy now</a> -->




			<div class="jumbotron jumbotron-fluid">
				<div class="container">
					<h1 class="display-6">Reviews</h1>
					
					@foreach ($reviews as $review)
					


					<hr>
					<p>review by : {{ $review->last_name }}</p>
					<p>date:  {{ $review->review_date }}</p>
					<p class="lead">
						 {{ $review->review_text  }} 

					</p>
					<hr>

					@endforeach



				</div>
			</div>
			
			<p hidden="true" id="postReviewUrl">{{ route('productController.addtocart') }}</p>
			<form method="POST" action="{{ route($postReview) }}">
				@csrf
				<div class="form-group">
					
					<div id="rating_id" class="w-100 mt-0 p-3 bg-light">
										<h4>Rate It</h4>
										<span onclick="rateCheck(this, 1)" class="fa fa-star text-warning rating"></span>
										<span onclick="rateCheck(this, 2)" class="fa fa-star text-warning rating"></span>
										<span onclick="rateCheck(this, 3)" class="fa fa-star text-warning rating"></span>
										<span onclick="rateCheck(this, 4)" class="fa fa-star text-warning rating"></span>
										<span onclick="rateCheck(this, 5)" class="fa fa-star rating"></span>

					</div>
					<input type="text" value="" hidden name="rating12" id="rating12">
					<input type="text" hidden value=" {{ $pid }} " name="productid"> 
					<input type="text" hidden value="{{ $uid }}" name="uid"> 
					<label for="exampleFormControlTextarea1" class=""> Add a Review </label>
					<textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="rev_text"></textarea>
					<input type="submit" value="Post Review" name="submit" class="btn btn-success mt-2">
				</div>
			</form>



			     @endif









	</div>
</div>
</div>
</div>




@endsection

