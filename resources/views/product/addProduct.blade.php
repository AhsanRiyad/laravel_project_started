@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')


<div class="col-lg-8 col-7 offset-1">

	<div class="row">
	<div class="col-lg-5 col-12 offset-lg-4 bg-secondary text-white p-5 rounded top-margin">
	
	<h3>Add Product</h3>

	<h4 class="text-warning" >{{ $msg }}</h4>

<form enctype="multipart/form-data" class="" method='post' action='#'>
  
  <div class="form-group">
    <h1 class="text-white">{{ session('msgfls') }}</h1>
    <label for="inputAddress">Product Name</label>
    
    <small class="text-danger">
                      {{ $errors->first('name') }} 
                    </small>

    <input name="name" type="text" class="form-control" id="inputAddress" placeholder="" value="{{ old('name') }}">
  </div>
  <div class="form-group">
    <label for="inputAddress" name="price">Price</label>

    <small class="text-danger">
                      {{ $errors->first('price') }} 
    </small>
    <input name="price" type="text" class="form-control" id="totalAmount" placeholder="" value="{{ old('location') }}">
  </div>

  <div class="form-group">
    <label for="inputAddress" name="price">Image</label>

    <small class="text-danger">
                      {{ $errors->first('img') }} 
    </small>
    <input name="img" type="file" class="form-control" id="totalAmount" placeholder="" value="{{ old('location') }}">
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




