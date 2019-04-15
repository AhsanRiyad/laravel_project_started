<!DOCTYPE html>
<html>
<head>
	<title>bootstrp </title>
  
  <!-- jquery ui -->
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.structure.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/jquery-ui.theme.css') }}">

  <!-- boostrap -->
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-reboot.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.css') }}">



  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	

  <!-- stylesheet -->
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
  
</head>
<body>

<p id="user_id" hidden="true" >1</p>
<p id='postReviewUrl'>
{{ route('AProductController.addtocart') }}</p>


<div class="row">
	<div class="col-3 offset-1">
	
	<h3>Point of Sales</h3>

	<form>
    @csrf
  
  <div class="form-group">
    <label for="inputState">Select User</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>

        @foreach($users as $user)
        <option> {{ $user->u_id }} - {{ $user->last_name }}</option>
        @endforeach

      </select>
  </div>
  <div class="form-group">
    <label for="inputAddress">Total</label>
    <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>

  <div class="form-group">
    <label for="inputState">Point of sell</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
  </div>

 <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Amount Paid</label>
      <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Amount Left</label>
      <input disabled type="password" class="form-control" id="inputPassword4" placeholder="Password">
    </div>
  </div>


  <button type="submit" class="btn btn-primary">Sign in</button>
</form>


	</div>


<div class="col-2">
	

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


  <button id="button_add_product" class="btn btn-primary">Sign in</button>



	</div>

	

<div class="col-5">
	
<h3>Product List</h3>

	
  
<div id='product_list_div'>

</div>
 
  






</div>





</div>




</body>






  <script src="{{ asset('js/jquery-3.3.1.js') }}" ></script>

    <!-- jquery ui -->
    <script src="{{ asset('js/jquery-ui.js') }}" ></script>

    <!-- bootstrap -->
    <script src="{{ asset('js/bootstrap.js') }}" ></script>
    <script src="{{ asset('js/bootstrap.bundle.js') }}" ></script>


<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

</body>
</html>