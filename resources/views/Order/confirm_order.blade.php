@php

$carouselImg1 = 'img/product1.jpg' ; 
$carouselImg2 = 'img/product2.jpg' ; 
$carouselImg3 = 'img/product3.jpg' ; 
$productImg = 'img/cat1.jpg';

@endphp



@extends('layout.hf')


@section('content')


<div class="row">
	<div class="col-6 offset-3">
		
		<div class="jumbotron bg-info text-white">
			<h1 class="display-4">One step left</h1>
			<p class="lead">Please select your payment method and your order will be on the way</p>
			<hr class="my-4">

			<form method="POST" action="{{ route('productController.confirmOrder') }}">
			<div class="radio">
				<label><input type="radio" name="optradio" value="cash" checked> Cash On Delivery</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="optradio" value="card"> Visa/Master Card</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="optradio" value="bkash" > bkash</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="optradio" value="nexus"> DBBL nexus</label>
			</div>



			<p class="lead">

				
				<input type="submit" class="btn btn-warning btn-lg "  value="Confirm Order">
				</form>

			</p>
		</div>

	</div>
</div>





@endsection
