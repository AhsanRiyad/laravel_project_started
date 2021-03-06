@php

$loginStatus=false;

@endphp

@extends('layout.hf')


@section('content')


<!-- registration page starts now -->
<form action="#" method="post">
	@csrf
	<div class="container-fluid">
		<div class="row justify-content-xl-center admin_background">
			<div class="col-12 col-xl-6 ">
				<div class="container">
					<div class="row pt-4 pb-1">
						
						<!-- @foreach($errors->all() as $e)
							{{ $e }}
						@endforeach -->
	
						
						@if($msg=='Welcome, Create your Umart Account')
						
						<p class="text-dark  h4" id='msg'>
							Welcome, Create your Umart Account
						</p>		

						@endif

						@if($msg=='Invalid email [server]')
						
						<p class="text-success  h4" id='msg'>
							'Invalid email [server]'
						</p>		

						@endif


						@if($msg=='invalid')
						
						<p class="text-success  h4" id='msg'>
							'Invalid email [server]'
						</p>		

						@endif


						@if($msg=='Invalid')
						
						<p class="text-success  h4" id='msg'>
							'Invalid email [server]'
						</p>		

						@endif


						@if($msg=='email already registered')
						
						<p class="text-danger  h4" id='msg'>
							Email already used
						</p>		

						@endif



						@if($msg=='success')
						
						<p class="text-success  h4" id='msg'>
							Reg successful
						</p>		

						@endif


						
						@if($msg=='null value')
						
						<p class="text-success  h4" id='msg'>
							Null value detected
						</p>		

						@endif

						
						<span class="ml-auto mt-auto pt-3"><small >Alredy member? <a href="{{ route('authentication.login') }}">Login</a> here</small></span>
					</div>

					<div class="row justify-content-xl-center bg-white py-5 mb-5">
						
						<!-- email input -->
						<div class="col-12 col-xl-6 ">

							<p class="text-danger h4 bg-white">
                 			{{ $errors->first('msg') }} 
             				 </p>

							<div class="form-group">
								<label > 

						@if($msg=='invalid')
						
						<small id="idExampleInputEmail1Small" style="color: red;"> Invalid Email [server]</small>

						@else
						
						<small id="idExampleInputEmail1Small" style="color: black;">Email*</small>

						<small class="text-danger">
                      {{ $errors->first('email') }} 
                    </small>
						
						@endif



	

									<br>


								</label>


								<input name="email" type="text" class="form-control rounded-0" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}">
							</div>
							<!-- password input -->
							<div class="form-group">
								<label for="exampleInputPassword1"><small id='idexampleInputPassword1'>Password*</small>

									<small class="text-danger">
                      			{{ $errors->first('password') }} 
                    				</small>
									<br>
								</label>
								<input name="password" type="password" class="form-control rounded-0" id="exampleInputPassword1" placeholder="Password" value="{{ old('password') }}">
							</div>

							<!-- re-enter password input -->
							<div class="form-group mb-4">
								<label for="exampleInputPassword1"><small>Re-enter password*</small>
								
								<small class="text-danger">
                      			{{ $errors->first('confirm_password') }} 
                    			</small>

								</label>
								<input name="confirm_password" type="password" class="form-control rounded-0" id="exampleInputPassword2" placeholder="Password" value="" >
							</div>

							<!-- month input -->
							<div hidden class="row">
								<div  class="col-3 pr-0">
									<small>
									DOB
									</small>

									<div class="input-group">
										<select name="month" class="custom-select rounded-0" id="inputGroupSelect01">

											<option>Month</option>
											<option >01</option>
											<option>02</option>
											<option >03</option>
											<option>04</option>
											<option >05</option>
											<option >06</option>
											<option >07</option>
											<option >08</option>
											<option >09</option>
											<option >10</option>
											<option  >11</option>
											<option  >12</option>
										</select>
									</div>
								</div>

								<!-- day input -->
								<div class="col-3 px-0">
									<small>&nbsp</small>
									<br>

									<div class="input-group">
										<select name="day" class="custom-select rounded-0 " id="inputGroupSelect01">
											<option>Day</option>
											<option >1</option>
											<option  >2</option>
											<option  >3</option>
											<option  >4</option>
											<option  >5</option>
											<option  >6</option>
											<option >7</option>
											<option >8</option>
											<option >9</option>
											<option >10</option>
											<option >11</option>
											<option  >12</option>
											<option  >13</option>
											<option  >14</option>
											<option  >15</option>
											<option  >16</option>
											<option >17</option>
											<option >18</option>
											<option >19</option>
											<option >20</option>
										</select>



									</select>
								</div>
							</div>

							<!-- yeas input -->
							<div class="col-3 pl-0 pr-2">
								<small>&nbsp</small>
								<div class="input-group">
									<select name="year" class="custom-select rounded-0" id="inputGroupSelect01">
										<option>Year</option>
										<option  >2008</option>
										<option  >2007</option>
										<option >2006</option>
										<option  >2005</option>
										<option  >2004</option>
										<option>2003</option>
										<option  >2002</option>
									</select>
								</div>
							</div>

							<!-- gender input -->
							<div class="col-3 pl-1">
								<small>User Type</small>
								
								

								<div class="input-group">
									<select  disabled name="user_type" class="custom-select rounded-0 pl-1 pl-lg-2 " id="inputGroupSelect01">
										<option  value="type">Type</option>
										<option selected value="admin">Admin</option>
										<option 
										
										value="user">User</option>
									
									</select>
								</div>
							</div>


						</div>

					</div>

					<!-- first name input -->
					<div class="col-12 col-xl-5 ">


					<label for="exampleInputEmail1" ><small id="idExampleInputEmail1Small">Country*

                

                </small>
                <small class="text-danger">
                      {{ $errors->first('country') }} 
                    </small>
                  <br>
          


                </label>

						<div class="input-group">



				



									<select  name="country" class="custom-select rounded-0 pl-1 pl-lg-2 " id="inputGroupSelect01">
										<option  value="">Country</option>
										<option selected value="bangladesh">Bangladesh</option>
										<option 
										
										value="india">India</option>
									
									</select>
								</div>

						<!-- last name input -->
						<div class="form-group mt-3">
							<label for="exampleInputEmail1"><small id="lnLabel">Last Name*</small>
								<small class="text-danger">
                      {{ $errors->first('last_name') }} 
                    </small>
						<br>
								

							</label>
							<input name="last_name"  type="text" class="form-control rounded-0" id="lnInput" aria-describedby="emailHelp" placeholder="Enter Last name" value="{{ old('last_name') }}">
						</div>
						
						<!-- mobile number input -->
						<div class="form-group mb-xl-3">
							<label for="exampleInputEmail1"><small id="exampleLabelMobile">Mobile Number*</small>

							<small class="text-danger">
                      {{ $errors->first('phone') }} 
                    </small>

								<br>
								
							</label>
							
							<input name="phone" type="text" class="form-control rounded-0" id="exampleInputMobile" aria-describedby="emailHelp" placeholder="Enter mobile number"
							value="{{ old('phone') }}">
						</div>

						<!-- toc terms and condition input -->
						<div class="custom-control custom-checkbox my-1 mr-sm-2 my-0 py-0">

							
							<input name="toc" value="yes" type="checkbox" class="custom-control-input" id="customControlInline"
							
							>
							<label class="custom-control-label" for="customControlInline"> 


						@if($checkbox=='invalid')
						
						<small id="idExampleInputEmail1Small" style="color: red;"> You must agree with the TOC [server]</small>

						@else
						
						<small id="idExampleInputEmail1Small" style="color: black;">Yes, I agree with TOC</small>
						
						@endif


							</label>
							
						</div>
						
						<!-- submit button -->
						<button type="submit" name="submit" value="submit" class="btn btn-success rounded-0 w-100 py-2 mt-3 mt-xl-1">Register</button>


						
							<!-- <p class="text-danger h5 mt-4"><i>Already have an account?</i></p>

								<a href="reg.php"><button type="button" class="btn btn-primary rounded-0 w-100 py-2">Register Here</button></a> -->

							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</form>



@endsection