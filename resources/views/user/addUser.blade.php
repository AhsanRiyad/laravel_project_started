@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')






	<div class="row">
	<div class="col-3 text-white offset-5 top-margin bg-info rounded p-5">
	
	<h3>Register User</h3>

	@if($msg=='null')
		
		
		<h1 align="center" class="text-danger"> Null Submission </h1>
		
		
		@elseif($msg=='added')
		
		
		<h1 align="center" class="text-success"> User Added </h1>
		
		
		@elseif ($msg == 'db'){%>
		<h1 align="center" class="text-danger"> DB problem </h1>

		
		@elseif ($msg == 'none')
		<h1 align="center" class="text-danger">  </h1>

		@endif

	<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputAddress">Customer Name</label>
    <input name="user_password" type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="user_email">Email</label>
    <input type="text" class="form-control" id="totalAmount" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress">Password</label>
    <input type="password" class="form-control" id="totalAmount" placeholder="1234 Main St">
  </div>

  <div class="form-group">
    <label for="inputState" >Type</label>
      <select name="user_type" id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option value="user">user</option>
        <option value="admin">admin</option>
      </select>
  </div>

	<input  class="btn btn-primary bg-primary" type="submit" name="submit" value="Register">
 
</form>
</div>
</div>











@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




