@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')


<div class="row">
  <div class="col-3 offset-5 top-margin bg-info rounded p-5 text-white">
  
  <h3>Money Transfer To Bangladesh</h3>

  <h4 class="text-white" > {{ $msg }} </h4>

  <form class="" method='post' action='#'>
  
  <div class="form-group">
    <label for="inputState" >Available Balance</label>
      <input name="balance" id="balance" type="text" value="{{ $balance[0]->balance_available }}" disabled class="form-control bg-success text-white">
  </div>

  <div class="form-group">
    <label for="inputState" >Sending To</label>
      <input name="balance" type="text" value="Bangladesh" disabled class="form-control bg-success text-white">
  </div>

  <div class="form-group">
    <label for="inputState" >Enter Amount</label>
      <input id="amount"  name="amount" id="amount" type="number" value="0" class="form-control">
  </div>
  
  <input type="text" hidden name="user_id" value="{{ $userinfo[0]['u_id'] }}">


  <input id="confirm_button" disabled class="btn btn-primary" type="submit" name="submit" value="Send Money">
 
</form>
</div>
</div>



@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection




