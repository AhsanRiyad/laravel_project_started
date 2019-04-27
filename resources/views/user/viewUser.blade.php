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
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Mobile</th>
      <th scope="col">Type</th>
      <th scope="col">Status</th>
      <th scope="col">delete</th>
      <th scope="col">update</th>
    </tr>
  </thead>
  <tbody>
    

	@foreach($users as $u)	

    <tr>
      <th scope="row"> {{ $u->u_id }} </th>
      <td> {{ $u->last_name }} </td>
      <td> {{ $u->u_mobile }} </td>
      <td> {{ $u->u_email }} </td>
      <td>{{ $u->u_type }}</td>
      <td>{{ $u->u_status }}</td>
      <td> 
        <form action="http://localhost:3000/user/deleteuser" method="post"> 
        
       <input type="text" name="userIdDelete" hidden="true" value="{{  $u->u_id  }}">

        <input type="submit" value="Delete" class="btn btn-danger">

      </form>
      </td>
      

      <td> 

        <a class="btn btn-primary" href="http://localhost:3000/user/updateuser/{{ $u->u_id }}">Update</a>

    </td>

    </tr>
    
    @endforeach
    
    <tr>
      <td>
        {{ $users->links() }}
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
