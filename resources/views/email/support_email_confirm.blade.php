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
		<div class="jumbotron bg-success text-white">
			<h1 class="display-4">We got your query !!!!</h1>
			<p class="lead">One of our correspondent will respond to you shortly</p>
			<hr class="my-4">
			<p>Thank You for being with Umart</p>
			<p class="lead">
				<a class="btn btn-primary btn-lg" href="http://localhost:3000" role="button">Shop More</a>
			</p>
		</div>
	</div>
</div>





@endsection
