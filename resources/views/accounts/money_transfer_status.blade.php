
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')

  <p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>




<div class="col-8">
  


<div class="row top-margin ">
  
  <div class="col-12">


  <h2 class="text-center bg-info text-white py-2 ">Shipment Request</h2>
  

     
  </div>


  <div class="col-12">
    


  <table class="table">
    <thead  class="thead-dark">
      <tr>
        <th>id</th>
        <th>Amount</th>
        <th>Transfer Date</th>
        <th>Sent By</th>
        <th>Received By</th>
        <th>Received Date</th>
        <th>Status</th>

      </tr>
    </thead>
    <tbody id="tbody">

    @foreach($money_transfer as $m)

    <tr>
      <td>{{ $m->id }}</td>
          <td>{{ $m->amount }}</td>
          <td>{{ $m->transfer_date }}</td>
          <td>{{ $m->transfered_by }}</td>
          <td>{{ $m->received_by }}</td>
          <td>{{ $m->receive_date }}</td>
      <td>
        
        @if ($m->status==0)
        <p class="text-white bg-warning text-center p-1 rounded" >pending </p>
        @else
         <p class="text-white bg-success text-center p-1 rounded" > approved</p>
        @endif

      </td>
    </tr>

      @endforeach
 

    </tbody>
  </table>
    </div>

</div>
<div id="dialog" title="Shipment Dtails">
</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>
 
@endsection
