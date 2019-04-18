@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection


<p hidden="true" id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>

@section('content')
<p id="user_id" hidden="true" >1</p>



<div class="row top-margin">
	


<div class="col-3 offset-3 p-5 t text-white bg-success">
	

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

  <button id="button_add_product_shipment" class="btn btn-primary">Reset</button>

   



	</div>

	

<div class="col-4 bg-secondary rounded-right text-white p-5">
	
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

