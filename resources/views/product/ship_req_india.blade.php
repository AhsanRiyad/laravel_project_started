
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')





<div class="container top-margin ">
  <h2 class="text-center bg-info text-white py-2 ">Shipment Request</h2>
  

 


  <table class="table">
    <thead class="thead-dark">
      <tr>
        <th>id</th>
        <th>Req Date</th>
        <th>Req From</th>
        <th>Details</th>
        <th>Accept</th>
        <th>Reject</th>

      </tr>
    </thead>
    <tbody>

    @foreach($ship_reqs as $reqs)

      <tr>
        <td>{{ $ship_reqs[0]->id }}</td>
        <td>{{ $ship_reqs[0]->req_date }}</td>
        <td>{{ $ship_reqs[0]->last_name }}</td>
        
        <td><button onclick="ship_details('{{ $ship_reqs[0]->id }}')" class="btn btn-success" >Details</button></td>
        <td><button class="btn btn-success" >Accept</button></td>
        <td><button class="btn btn-danger" >Reject</button></td>
      </tr>

  @endforeach

    </tbody>
  </table>


<div id="dialog" title="Basic dialog">
</div>
@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>
    
    


    <script>
      
    function ship_details(id)
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
   

    }

    </script>


@endsection
