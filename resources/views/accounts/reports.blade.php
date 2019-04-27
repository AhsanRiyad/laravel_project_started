
@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')

   <p id="user_id" hidden >{{ $userinfo[0]['u_id'] }}</p>



<div class="col-8 offset-1">
  



<div class="row top-margin ">


  <div class="col-12">
    
 
  <h2 class="text-center bg-info text-white py-2 ">Daily Sales Report</h2>
  
 </div>
 

<div class="col-12">
  

  <table class="table">
    <thead  class="thead-dark">
      
        <th>id</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Total tk</th>
        <th>tk_paid</th>
        <th>Details</th>
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
          <button onclick="order_details('{{ $r->order_id }}')"  class="btn btn-info">
            Details
          </button>
          
        </td>

        <td>
          {{ $r->due }}
          
        </td>
          
      </tr>


      @endforeach
  

    
    
      <!-- <tr>
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
      
        
      </td> -->


      <td>
        <a href="{{ route('accountController.money_transfer_status') }}">
        <button class="btn btn-info">
          Money Transfer Log  
        </button>
        </a> 
      </td>

      <td>
        <a href="{{ route('accountController.all_sales') }}">
        <button  class="btn btn-info px-5">
          All Sales 
        </button>
        </a> 
      </td>

    </tr>

    <tr>
      <td>
        {{ $reports->links() }}
      </td>
    </tr>
    

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
