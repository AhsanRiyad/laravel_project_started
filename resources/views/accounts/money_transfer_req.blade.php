
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
    
 

  <h2 class="text-center bg-info text-white py-2 ">Money Transfer
  
  <br>
  {{ $msg }}


  </h2>
  

  </div>

  <div class="col-12">
    
 

  <table class="table">
    <thead  class="thead-dark">
      <tr>
        <th>id</th>
        <th>Transfer Date</th>
        

        <th>Amount</th>

        <th>Approve</th>

      </tr>
    </thead>
    <tbody id="tbody">

    @foreach($money as $m)

      <tr>
        <td>{{ $m->id }}</td>
        <td>{{ $m->transfer_date }}</td>
        <td>{{ $m->amount }}</td>
    
        <td>
          <form method="post" action="#">
            @csrf
            <input type="text" name="admin_id" hidden value="{{$userinfo[0]['u_id']}}" >
            <input type="text" value="{{ $m->id  }}" hidden name="id">
            
            <input type="submit" class="btn btn-success" name="submit" value="accept">

        </form>
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
