<!DOCTYPE html>
<html>
<head>
	<title>bootstrp </title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	

  <!-- stylesheet -->
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
  
</head>
<body>

<p id="user_id" hidden="true" >1</p>
<p id='postReviewUrl'>http://localhost:3000/a_cart</p>

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

	<form>
  @csrf

 <div class="form-row">
    
    <div class="form-group col-md-2">
      <label for="inputEmail4">Product id</label>
      <input disabled type="email" class="form-control" id="inputEmail4" placeholder="Email">
    </div>
    <div class="form-group col-md-2">
      <label for="inputPassword4">Product Name</label>
      <input  type="password" class="form-control" id="inputPassword4" placeholder="Password">
    </div>

	<div class="form-group col-md-2">
    <label for="inputState">Quantity</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
  </div>
	<div class="form-group col-md-2">
    <label for="inputState">Update</label>
       <input type="submit" value="update" class="btn btn-primary form-control">
	</div>
	
	<div class="form-group col-md-2">
    <label for="inputState">Update</label>
       <input type="submit" value="update" class="btn btn-primary form-control">
	</div>
  </div>


 
</form>


</div>





</div>




</body>






<script
		src="https://code.jquery.com/jquery-3.4.0.js"
		integrity="sha256-DYZMCC8HTC+QDr5QNaIcfR7VSPtcISykd+6eSmBW5qo="
		crossorigin="anonymous">
</script>

<script
	src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
	integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
	crossorigin="anonymous">
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

</body>
</html>