<!DOCTYPE html>
<html>
<head>
	<title>Order Confirm PDF</title>
</head>
<body>

<h1>Order Invoice</h1>
<table class="table" border="1">
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
			<td>{{ $products[0][$i]->product_price*$products[0][$i]->product_qntity }}

			
			</td>

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

</body>
</html>