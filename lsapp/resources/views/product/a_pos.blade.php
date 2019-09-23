@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



<p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>
<p hidden="true" id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>


  @section('content')
  
  <div class="col-lg-8 col-7 offset-1 ">
    
 
  <div class="row top-margin">
    
  <div class="col-12 text-center bg-dark text-white">
    <h1 class="p-2">Point of Sales</h1>
  </div>

	<div class="col-lg-3 col-12  p-5 rounded-left bg-info text-white">
	
	<h3>Point of Sales</h3>

	
  
  <div class="form-group">
    <label for="inputState">Select User</label>
      <select id="user_id_input" class="form-control">
        <option selected value=''>Choose...</option>

        @foreach($users as $user)
        <option value='{{ $user->u_id }}' > {{ $user->u_id }} - {{ $user->last_name }}
        </option>
        @endforeach

      </select>
  </div>
  <div class="form-group">
    <label for="inputAddress">Total</label>
    <input disabled="true" type="text" class="form-control" id="totalAmount" placeholder="0">
  </div>

  <div class="form-group">
    <label for="inputState">Sell's Point </label>
      <select id="salesPoint_id" class="form-control">
        <option selected>Choose...</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Kurigram">Kurigram</option>
        <option value="Rangpur">Rangpur</option>
      </select>
  </div>

 <div class="form-row">
    <div class="form-group col-6">
      <label for="">Paid</label>
      <input type="text" class="form-control" id="amount_paid_input" placeholder="0">
    </div>
    <div class="form-group col-6">
      <label for="">Left</label>
      <input disabled type="text" class="form-control" id="amount_left_input" placeholder="0">
    </div>
  </div>


  <button id="button_confirm_order" class="btn btn-primary">Confirm Order</button>



	</div>


<div class="col-lg-4 col-12 p-5 t text-white bg-success">
	

	<h3>Select Product</h3>


  <div class="form-group">
    <label for="inputState">Products</label>
      <select id="select_products" class="form-control">
        <option selected>Choose...</option>

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


  <button id="button_add_product" class="btn btn-primary">Add Product</button>

  <button id="button_reset_product" class="btn btn-primary">Reset</button>

   <button  id="button_show_product" class="btn btn-primary">Show</button>




	</div>

	

<div class="col-lg-5 col-12 bg-secondary rounded-right text-white p-5">
	
<h3>Product List</h3>

	
  
<div id='product_list_div'>

</div>
 

</div>
 </div>

</div>


@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection
