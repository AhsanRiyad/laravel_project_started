
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
        <th>Req Date</th>
        <th>Req From</th>
        <th>Details</th>
        <th>Accept</th>
        <th>Reject</th>

      </tr>
    </thead>
    <tbody id="tbody">

    @foreach($ship_reqs as $reqs)

      <tr>
        <td>{{ $reqs->id }}</td>
        <td>{{ $reqs->req_date }}</td>
        <td>{{ $reqs->last_name }}</td>
        
        <td><button onclick="ship_details('{{ $reqs->id }}')" class="btn btn-success" >Details</button></td>
        <td><button onclick="ship_accept('{{ $reqs->id }}')" class="btn btn-success" >Accept</button></td>
        <td><button onclick="ship_reject('{{ $reqs->id }}')" class="btn btn-danger" >Reject</button></td>
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
    
    


    <script>
      
    /*function ship_details(id)
    { 
      $('#dialog').html(id);

      $( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      width: 630,
      position: { my: 'top', at: 'top+150' },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
 
   
      $( "#dialog" ).dialog( "open" );
   

    }*/

    </script>


@endsection
