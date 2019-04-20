@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">



@endsection


<p hidden="true" id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>

@section('content')




<div class="col-8">
  


<div class="row top-margin">
	


<div class="col-5 offset-2  p-5 t text-white bg-success">
	

	<h3>Select Product</h3>

  <p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>
  <div class="form-group">
    <label for="inputState">Products</label>
      <select id="select_products" class="form-control">
        <option selected value="0">Choose...</option>

        @foreach($products as $product)
        <option> {{ $product->product_id }} - {{ $product->product_name }}</option>
        @endforeach

      </select>
  </div>

<div class="form-group">
    <label for="inputState">Quantity</label>
      <select id="select_product_qntity" class="form-control">
        <option value="0" selected>Choose...</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
  </div>


  <button id="button_add_product_shipment" class="btn btn-primary">Add Product</button>

  <button id="button_reset_product_shipment" class="btn btn-primary">Reset</button>

  <button id="button_request_shipment" class="btn btn-primary">Request Shipment</button>



	</div>

	

<div class="col-5 bg-secondary rounded-right text-white p-5">
	
<h3>Product List</h3>

	
  
<div id='product_list_div'>

  @if($productList !=[])

  @foreach($productList as $p)

  <div class="form-row"> 
            
            <div class="form-group col-md-2"> 
            <label for="inputEmail4"> id</label> 
            <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email" value='{{$p->product_id}}'> 
            </div>
            <div class="form-group col-md-2"> 
            <label for="inputPassword4">Name</label>
            <input disabled type="text" class="form-control" id="inputPassword4" placeholder="Password" value="{{ $p->product_name }}">
            </div>
            
            <div class="form-group col-md-2">
            <label for="inputState">Quantity</label>
            <select onchange="changeQntity(this);" id="ship_quantity" class="form-control">
            <option value="0" >Choose...</option>
            <option value="1" >1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
            <option value="4" >4</option>
            <option selected value='{{$p->product_quantity}}' >{{$p->product_quantity}}</option>
            </select>
            </div>
            <div class="form-group col-md-2">
            <label for="inputState">Update</label>
            <button onClick = "update_it_ship('{{$p->id}}');"  class="btn btn-primary form-control">Update</button>
            </div>
            
            <div class="form-group col-md-2">
            <label for="inputState">Delete</label>
            
            <a onClick = "delete_it_ship({{$p->id}});"><button class="btn btn-primary form-control">Delete</button></a>
            </div>
            </div>
  

  @endforeach

  @endif

</div>
 

</div>

</div>



</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection

