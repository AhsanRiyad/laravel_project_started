
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
    
 
  <h2 class="text-center bg-info text-white py-2 ">All Sales Report</h2>
  
 </div>
 

<div class="col-12">
  

 
  <table class="table">
    <thead  class="thead-dark">
      
        <th>id</th>
        <th>Customer Name</th>
        <th>Order Date</th>
        <th>Total tk</th>
        <th>tk_paid</th>
        <th>Due</th>
        <th>Details</th>
        <th>Reports</th>
        

      
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
        <td>
          <button onclick="order_details('{{ $r->order_id }}')"  class="btn btn-info">
            Details
          </button>
          
        </td>
        <td>
          <form action="{{ route('accountController.dowload_report') }}" method="post">

               <input hidden type="text" value="{{ $r->user_id }}" name="user_id">
               <input hidden type="text" value="{{ $r->total_amount }}" name="total_amount">
                <input hidden type="text" value="{{ $r->order_date }}" name="order_date">
              <input hidden type="text" value="daily_sales" name="page_name">
             <input hidden type="text" value="{{ $r->order_id }}" name="order_id">
            <input class="btn btn-info" type="submit" value="Download" name="submit">
          </form>
          
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
        <a href="{{ route('accountController.sales_report') }}">
        <button  class="btn btn-info px-5">
          Daily Sales 
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
