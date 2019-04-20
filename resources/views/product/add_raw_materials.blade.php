@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')

<div class="col-8">
  



<div class="row">
	<div class="col-4 offset-4 top-margin bg-info rounded p-5 text-white">
	
	<h3>Add Raw Materials</h3>

  <h4 class="text-white" > {{ $msg }} </h4>

	<form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputState" >Raw Materials</label>
      <select name="materials" id="inputState" class="form-control">
        <option value="0" selected>Choose...</option>

        @foreach($rawMaterials as $r)

        <option value="{{ $r->id }}">{{ $r->id }} - {{$r->name}}</option>
        @endforeach

      </select>
  </div>

	<div class="form-group">
    <label for="inputState" >Factory</label>
      <select name="factory_name" id="inputState" class="form-control">
        <option value="0" selected>Choose...</option>
        @foreach($factories as $r)
        <option value="{{ $r->id }}">{{ $r->id }} - {{ $r->name }}</option>

        @endforeach
        
      </select>
  </div>


  <div class="form-group">
    <label for="inputState" >Quantity</label>
      <select name="quantity" id="inputState" class="form-control">
        <option value="0" selected>Choose...</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">20</option>
      </select>
  </div>



	<input  class="btn btn-primary" type="submit" name="submit" value="Add Raw Materials">
 
</form>
</div>
</div>
</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




