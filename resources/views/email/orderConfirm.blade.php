<!DOCTYPE html>
<html>
<head>
	<title>Order Confirm PDF</title>
</head>
<body>

<h1>Order Invoice</h1>
<h2>Order_no# {{ $order_details[0]->order_id }} </h2>
<h2>Date# {{ $date }} </h2>
<table class="table" border="1">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>

			<th scope="col">ID</th>
			<th scope="col">Name</th>
			<th scope="col">Quantity</th>
			<th scope="col">Price</th>
		</tr>
	</thead>
	<tbody>







	
		@for($i = 0 ; $i<count($order_details); $i++)
		




		<tr>

			<th scope="row">{{ $i+1 }}  </th>				

			<td>{{ $order_details[$i]-> product_id }}</td>
			<td>{{ $order_details[$i]-> product_name  }}</td>
			<td>{{$order_details[$i]->qntity  }}</td>
			<td>{{ $order_details[$i]->product_sell_price*$order_details[$i]->qntity }}

			
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

</body>
</html>