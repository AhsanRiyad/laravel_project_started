@php

$productImg = 'img/product_demo.png';
$postReview = "product.postReview";
@endphp

@extends('layout.hf')


@section('content')



<div class="container  bg-light  pb-5" style="margin-bottom: -20px">
	<div class="row justify-content-md-center">
		<div class="col-12 col-md-6  mt-5 ">
			
			<h1>
				Send us msg for any help
			</h1>
			

			<form action="{{ route('productController.support') }}" method="post">
				
				<div class="form-group">
					<label for="exampleInputEmail1">Your Name:</label>
					<input name="name" hidden type="text" class="form-control"  value="{{ $name }}">

					<input name="" disabled type="text" class="form-control"  aria-describedby="emailHelp" placeholder="Enter Name" value="{{ $name }}">

				</div>				

				<div class="form-group">
					<label for="exampleInputEmail1">Email address</label>

					<input name="email"  hidden class="form-control"   value="{{ $email }}">

					<input name="" disabled  class="form-control"   value="{{ $email }}">
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
				</div>				
						

				<div class="form-group">
					<label for="inputState" >Subject</label>
					<small class="text-white bg-danger">
                      {{ $errors->first('subject') }} 
                    </small>
					<select name="subject" id="inputState" class="form-control">
						<option  selected value="">Choose...</option>
						<option value="general issue">General Issue</option>
						<option value="order issue">Order Issue</option>
						<option value="order issue">Others</option>
					</select>
				</div>


				<div class="form-group">
					<label for="exampleFormControlTextarea1">Write Your msg:</label>
					<small class="text-white bg-danger">
                      {{ $errors->first('msg') }} 
                    </small>
					<textarea  name="msg" class="form-control"  rows="3"> {{ old('msg') }} </textarea>
				</div>

				<input type="submit" class="btn btn-success" value="Send" >
			</form>

			<p class="p_style ">Visit our FAQ</p>
			<button class="btn btn-primary">FAQ</button>

		</form>
	</div>
</div>
</div>









@endsection
