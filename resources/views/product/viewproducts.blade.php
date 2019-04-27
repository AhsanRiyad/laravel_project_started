@extends('layout.hf_dboard')

  <!-- stylesheet -->
@section('stylesheet')
  <link rel="stylesheet" href="{{ asset('/css/a_style.css') }}">
@endsection



@section('content')


<div class="col-lg-8 col-7 offset-1">
  

<div class="row">
  <div class="col-12 top-margin">


		<h1 class="text-secondary text-center">user List</h1>
	<table class="table">
  <thead class="thead-light">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Product ID</th>
      <th scope="col">Product Name</th>
      <th scope="col">View Reviews</th>
    </tr>
  </thead>
  <tbody>
    
   <p hidden>{{ $i=1 }}</p>
	@foreach($products as  $p)

    <tr>
      <th scope="row"> {{ $i++ }} </th>
      <td> {{ $p->product_id }} </td>
      <td> {{ $p->product_name }}  </td>
      <td> 
        <a class="btn btn-primary" href="{{ route('product.view_review' , [$p->product_id]) }}">View Review</a>
       </td>
      
      <!-- <td> 
        <form action="http://localhost:3000/user/deleteuser" method="post"> 
        
       <input type="text" name="userIdDelete" hidden="true" value="">

        <input type="submit" value="Delete" class="btn btn-danger">

      </form> -->
      <!-- </td> -->
      

      

    </tr>
    
    @endforeach

    <tr>
      <td>
        {{ $products->links() }}
      </td>
    </tr>


  </tbody>
</table>


	</div>
</div>




@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection