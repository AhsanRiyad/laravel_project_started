




@php

$carouselImg1 = 'img/product1.jpg' ; 
$carouselImg2 = 'img/product2.jpg' ; 
$carouselImg3 = 'img/product3.jpg' ; 
$productImg = 'img/cat1.jpg';

@endphp



@extends('layout.hf')


@section('content')





<div class="container">
	

	@if(count($products[0]) < 1)

	<div class="jumbotron jumbotron-fluid mt-4 bg-info text-white">
		<div class="container">
			<h1 class="display-4">Your cart is empty</h1>
			<p class="lead">Please add some product to the cart and come here again</p>
		</div>
	</div>


@else

<table class="table">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>

			<th scope="col">Name</th>
			<th scope="col">Short Description</th>
			<th scope="col">Quantity</th>
			<th scope="col">Price</th>
		</tr>
	</thead>
	<tbody>







	
		@for($i = 0 ; $i<count($products[0]); $i++)
		




		<tr>

			<th scope="row">{{ $i+1 }}  </th>				

			<td>{{ $products[0][$i]-> product_name }}</td>
			<td>{{ $products[0][$i]-> descriptions  }}</td>
			<td>{{$products[0][$i]->product_qntity  }}</td>
			<td>{{$products[0][$i]->product_price }}</td>

		</tr>


	@endfor

	<tr>
		<th scope="row"></th>
		<td></td>
		<td></td>
		<td>Total Amount:</td>
		<td>{{ $products[1][0] -> total }}  </td>


	</tr>



</tbody>
</table>

<hr>





<a href="http://localhost:3000/order/confirm" class="text-dark btn btn-warning">Confirm Order</a>

@endif






<hr>






<a href="http://localhost:3000" class="btn btn-info mt-1">Continue Shopping</a>


</div>



@endsection

