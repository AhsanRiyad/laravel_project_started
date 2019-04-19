
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')

  <p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>




<div class="container top-margin ">
  <h2 class="text-center bg-info text-white py-2 ">Shipment Request</h2>
  

 


  <table class="table">
    <thead  class="thead-dark">
      <tr>
        <th>id</th>
        <th>Requset Date</th>
        <th>Approve Date</th>
        <th>Status</th>

      </tr>
    </thead>
    <tbody id="tbody">

    @foreach($shipment_log as $s)

    <tr>
      <td>{{ $s->id }}</td>
          
          <td>{{ $s->req_date }}</td>
          <td>{{ $s->acc_date }}</td>
      <td>
        
        @if ($s->status==0)
        <p class="text-white bg-warning text-center p-1 rounded" >pending </p>
        @elseif($s->status==1)
        <p class="text-white bg-success text-center p-1 rounded" > approved</p>
        @else
         <p class="text-white bg-danger text-center p-1 rounded">rejected </p>
        @endif

      </td>
    </tr>

      @endforeach
 

    </tbody>
  </table>


<div id="dialog" title="Shipment Dtails">
</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>
 
@endsection