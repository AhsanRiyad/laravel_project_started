@php

$loginStatus=false;

@endphp

@extends('layout.hf')


@section('content')


<!-- login form starts -->
<form  method="post" action="{{ route('authentication.login' ) }}">

  @csrf

  <div class="container-fluid">
    <div class="row justify-content-xl-center admin_background">
      <div class="col-12 col-xl-6 ">
        <div class="container">
          <div class="row py-4">
            
<!-- 
            @foreach($errors->all() as $e)
                {{ $e }}

            @endforeach
               -->
              
              @if($validCheck == 'true')
              
              <p class="text-dark h4" id="login_id">
              Welcome to Umart! Please login
              
              @elseif ($validCheck == 'user')
              
              <p class="text-white bg-danger "> User Can not login <br>
                only admin is allowed

              @else
               
               <p class="text-danger h4 bg-white" id="login_id">
              Email/Password Not Matched
              
              @endif

              </p>

              <br>

              


          </div>

          <div class="row justify-content-xl-center bg-white py-5 mb-5">

            <div class="col-12 col-xl-6 ">
              <p class="text-danger h4 bg-white">
                 {{ $errors->first('msg') }} 
              </p>
              <div class="form-group">
                <label for="exampleInputEmail1" ><small id="idExampleInputEmail1Small">Email address*

                

                </small>
                <small class="text-danger">
                      {{ $errors->first('email') }} 
                    </small>
                  <br>
          


                </label>
                <input name='email'  type="text" class="form-control rounded-0" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email"
                value=""
                >
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1"><small>Password* 
                </small>
                    
                    <small class="text-danger font-weight-bold" id="password_error" >
                      {{ $errors->first('password') }} 
                    </small>
                  <br>
                


                </label>
                <input name="password" type="password" class="form-control rounded-0" id="exampleInputPassword1" placeholder="Password"
                value=""

                >
                <p class="text-right text-danger">
                  <small><a href="">Forgot password?</a></small>
                </p>
              </div>

            </div>
            <div class="col-12 col-xl-5 align-self-xl-center">
              <button type="submit" name="submit" value="submit" class="btn btn-success rounded-0 w-100 py-2">Log In</button>

              <p class="text-danger h5 mt-2"><i>Not a member yet?</i></p>

              <a href="{{ route('authentication.signup') }}"><button type="button" class="btn btn-primary rounded-0 w-100 py-2">Register Here</button></a>

            </div>


          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- body ends-->




@endsection
