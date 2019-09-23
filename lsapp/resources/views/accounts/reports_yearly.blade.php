
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')

  <p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>




<div class="container top-margin ">
  <h2 class="text-center bg-info text-white py-2 ">Daily Sales Report</h2>
  

 


  <table class="table">
    <thead  class="thead-dark">
      
        <th>id</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Total tk</th>
        <th>tk_paid</th>
        <th>Due</th>
        

      
    </thead>
    <tbody id="tbody">
    

      @foreach($reports as $r)
      <tr>
        <td>
          {{ $r->order_id }}
          
        </td>

        <td>
          {{ $r->last_name }}
          
        </td>

        <td>
          {{ $r->order_date }}
          
        </td>

        <td>
          {{ $r->total_amount }}
          
        </td>

        <td>
          {{ $r->paid }}
          
        </td>

        <td>
          {{ $r->due }}
          
        </td>
          
      </tr>


      @endforeach
  

    
   <tr>
     <td>
       <a href="">
       <button class="btn btn-info">
         Monthly Sales Report    
       </button>
       </a>
   
   
     </td>
   
     <td>
       <a href="">
       <button class="btn btn-info">
         Yearly Sales Report    
       </button>
       </a>
   
       
     </td>


      <td>
        <a href="{{ route('accountController.money_transfer_status') }}">
        <button class="btn btn-info">
          Money Transfer Log  
        </button>
        </a> 
      </td>

    </tr>
    

    </tbody>
    


  </table>




@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>
 
@endsection
