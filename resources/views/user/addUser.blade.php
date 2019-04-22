@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')




<div class="col-lg-8 col-7 offset-1">
  


	<div class="row">
	<div class="col-lg-5 col-12 text-white offset-lg-3 top-margin bg-info rounded p-5">
	
	<h3>Register User</h3>

	<h4 class="text-warning" >{{ $msg }}</h4>

	<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputAddress">Customer Name</label>
    <small class="text-danger">
                      {{ $errors->first('name') }} 
                    </small>
    <input name="name" type="text" class="form-control" id="inputAddress" placeholder="" value="{{ old('name') }}">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="user_email">Email</label> <small class="text-danger">
                      {{ $errors->first('email') }} 
                    </small>
    <input name="email" type="text" class="form-control" id="totalAmount" placeholder="" value="{{ old('email') }}">
  </div>
  <div class="form-group">
    <label for="inputAddress">Password</label>
    <small class="text-danger">
                      {{ $errors->first('password') }} 
                    </small>
    <input type="password" class="form-control" id="totalAmount" placeholder="" name="password" value="{{ old('password') }}">
  </div>

  <div class="form-group">
    <label for="inputState" >Type</label>
    <small class="text-danger">
                      {{ $errors->first('type') }} 
                    </small>
      <select name="type" id="inputState" class="form-control">
        <option selected value="{{ old('type') }}">Choose...</option>
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




