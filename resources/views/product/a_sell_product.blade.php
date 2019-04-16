@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')
<p id="user_id" hidden="true" >1</p>
<p hidden="true" id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>


<div class="row top-margin">
	<div class="col-2 offset-2 p-5  bg-warning">
	
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
    <label for="inputState">Sell's Point</label>
      <select id="salesPoint_id" class="form-control">
        <option selected>Choose...</option>
        <option value="New Delhi">New Delhi</option>
        <option value="Agrabad">Agrabad</option>
        <option value="Banglore">Banglore</option>
      </select>
  </div>

 <div class="form-row">
    <div class="form-group col-md-6">
      <label for="">Amount Paid</label>
      <input type="text" class="form-control" id="amount_paid_input" placeholder="0">
    </div>
    <div class="form-group col-md-6">
      <label for="">Amount Left</label>
      <input disabled type="text" class="form-control" id="amount_left_input" placeholder="0">
    </div>
  </div>


  <button id="button_confirm_order" class="btn btn-primary">Confirm Order</button>



	</div>


<div class="col-3 p-5  bg-success">
	

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
        <option selected>Choose...</option>
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
      </select>
  </div>


  <button id="button_add_product" class="btn btn-primary">Add Product</button>

  <button id="button_reset_product" class="btn btn-primary">Reset</button>

   <button  id="button_show_product" class="btn btn-primary">Show</button>




	</div>

	

<div class="col-4 bg-info p-5">
	
<h3>Product List</h3>

	
  
<div id='product_list_div'>

</div>
 
  






</div>





</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection

