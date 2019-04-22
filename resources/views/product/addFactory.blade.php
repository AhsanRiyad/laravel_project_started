@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')


<div class="col-lg-8 col-7 offset-1">

	<div class="row">
	<div class="col-lg-5 col-12 offset-lg-4 bg-secondary text-white p-5 rounded top-margin">
	
	<h3>Add Factory</h3>

	<h4 class="text-warning" >{{ $msg }}</h4>

<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputAddress">Factory Name</label>
    
    <small class="text-danger">
                      {{ $errors->first('name') }} 
                    </small>

    <input name="name" type="text" class="form-control" id="inputAddress" placeholder="" value="{{ old('name') }}">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="user_email">Location</label>

    <small class="text-danger">
                      {{ $errors->first('location') }} 
                    </small>
    <input name="location" type="text" class="form-control" id="totalAmount" placeholder="" value="{{ old('location') }}">
  </div>
  
<input  class="btn btn-primary" type="submit" name="submit" value="Add Factory">
 
</form>
</div>
</div>






</div>




@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




