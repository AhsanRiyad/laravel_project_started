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
      <th scope="col">Reviews</th>
      <th scope="col">Date</th>
      <th scope="col">User Id</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
    
<p hidden >{{ $i=1 }}</p>
	@foreach($review as $r)

    <tr>
      <th scope="row"> {{ $i++ }} </th>
      <td> {{ $r->review_text }} </td>
      <td> {{ $r->review_date }} </td>
      <td> {{ $r->user_id }} </td>
      <td> 

      	<form method="POST" action="http://localhost:3000/product/up_rev/<%=allReviews[i].product_id%>">

      	<input type="text" name="rev_id" hidden="true" value="<%=allReviews[i].review_id%>">

        <input type="submit" class="btn btn-danger" name="submit" value="delete">

        </form>

       </td>
      
      
      

      

    </tr>
    
  @endforeach


  <tr>
    <td>
      {{ $review->links() }}
    </td>
  </tr>


  </tbody>
</table>


	</div>
</div>

</div>

@endsection

@section('script')
<!-- main js -->
    <script src="{{ asset('js/a_main.js') }}" ></script>

@endsection

