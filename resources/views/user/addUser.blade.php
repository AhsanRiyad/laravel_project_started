@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')




<div class="col-8">
  


	<div class="row">
	<div class="col-4 text-white offset-4 top-margin bg-info rounded p-5">
	
	<h3>Register User</h3>

	<h4 class="text-warning" >{{ $msg }}</h4>

	<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputAddress">Customer Name</label>
    <input name="name" type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="user_email">Email</label>
    <input name="email" type="text" class="form-control" id="totalAmount" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress">Password</label>
    <input type="password" class="form-control" id="totalAmount" placeholder="1234 Main St" name="password">
  </div>

  <div class="form-group">
    <label for="inputState" >Type</label>
      <select name="type" id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option value="user">user</option>
        <option value="admin">admin</option>
      </select>
  </div>

	<input  class="btn btn-primary bg-primary" type="submit" name="submit" value="Register">
 
</form>
</div>
</div>

</div>









@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




