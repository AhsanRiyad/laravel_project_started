




@php

$carouselImg1 = 'img/product1.jpg' ; 
$carouselImg2 = 'img/product2.jpg' ; 
$carouselImg3 = 'img/product3.jpg' ; 
$productImg = 'img/cat1.jpg';

@endphp



@extends('layout.hf')


@section('content')





<div class="container">
	

	@if(count($products) < 1)

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







	
		@for($i = 0 ; $i<count($products); $i++)
		




		<tr>

			<th scope="row">{{ $i+1 }}  </th>				

			<td>{{ $products[$i]-> product_name }}</td>
			<td>{{ $products[$i]-> descriptions  }}</td>
			<td>{{$products[$i]->quantity  }}</td>
			<td>{{ $products[$i]->product_price*$products[$i]->quantity }}

			
			</td>

		</tr>


	@endfor

	<tr>
		<th scope="row"></th>
		<td></td>
		<td></td>
		<td>Total Amount:</td>
		<td>{{ $total }}  </td>


	</tr>



</tbody>
</table>

<hr>





<a href="{{ route('productController.confirmOrder') }}" class="text-dark btn btn-warning">Confirm Order</a>

@endif






<hr>






<a href="{{ route('index') }}" class="btn btn-info mt-1">Continue Shopping</a>


</div>



@endsection

