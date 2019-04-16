@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')






	<div class="row">
	<div class="col-3 offset-5 top-margin">
	
	<h3>Add Raw Materials</h3>

	<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputState" >Raw Materials</label>
      <select name="user_type" id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option value="user">Cement</option>
        <option value="admin">Sand</option>
      </select>
  </div>

	<div class="form-group">
    <label for="inputState" >Factory</label>
      <select name="user_type" id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option value="user">Dhaka</option>
        <option value="admin">Bogra</option>
        <option value="admin">Rangpur</option>
      </select>
  </div>


  <div class="form-group">
    <label for="inputState" >Quantity</label>
      <select name="user_type" id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option value="user">10</option>
        <option value="admin">20</option>
      </select>
  </div>

 

	<input  class="btn btn-primary" type="submit" name="submit" value="Add Raw Materials">
 
</form>
</div>
</div>











@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




