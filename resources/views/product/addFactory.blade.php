@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')




	<div class="row">
	<div class="col-3 offset-5 top-margin">
	
	<h3>Add Factory</h3>

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
    <label for="inputAddress">Factory Name</label>
    <input name="user_password" type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="user_email">Location</label>
    <input type="text" class="form-control" id="totalAmount" placeholder="1234 Main St">
  </div>
  
<input  class="btn btn-primary" type="submit" name="submit" value="Register">
 
</form>
</div>
</div>











@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




