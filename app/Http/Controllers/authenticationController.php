<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\authenticationModel;
use App\Http\Requests\regRequest;

class authenticationController extends Controller
{
    //
    public static function login(Request $req){

    	$user = ['msg' => 'Welcome, Create your Umart Account' , 'loginStatus' => false] ; 
    	$user['validCheck'] = true; 
    	return view('authentication/login' , $user);

    }

    public static function logout(Request $req){
        $req->session()->forget('userinfo');
        $user = ['msg' => 'Welcome, Create your Umart Account' , 'loginStatus' => false] ; 
        $user['validCheck'] = true; 
        return redirect()->route('authentication.login');

    }




    public static function loginCheck(Request $req){



    	//parameter receive
    	$user = [ 'email' => $req->email  , 'password' => $req->password ] ; 

    	$user['validCheck'] = false; 
    	//session


    	//database checking
    	$userDetails = authenticationModel::loginCheck($user);

    	if($userDetails!=[])
    	{	
    		$user['validCheck'] = true; 

            session(['userinfo'=>$userDetails]);
            $userinfo = session('userinfo');
    		return redirect()->route('dashboardController.dashboard');
    	}else{
    		$user['validCheck'] = false; 
    		return view('authentication/login' , $user);
    	}


    	//return $userDetails;


    	

    }

    public static function signUp(Request $req){

        $user = ['msg' => 'Welcome, Create your Umart Account' , 'loginStatus' => false] ;
        $user['checkbox'] = '';  
        $user['validCheck'] = true; 
        $obj['nullVal'] = false;
        $obj['regSuceess'] = 'other';;

        return view('authentication/registration' , $user);

    }

   public static function signUpCheck(regRequest $req){

        $user = ['msg' => 'Welcome, Create your Umart Account' , 'loginStatus' => false] ;
        $user['checkbox'] = '';  
        $user['validCheck'] = true; 


        $userinfo = [];
        $userinfo['email'] = $req->email;
        $userinfo['password'] = $req->password;
        $userinfo['month'] = $req->month;
        $userinfo['day'] = $req->day;
        $userinfo['year'] = $req->year;
        $userinfo['gender'] = $req->gender;
        $userinfo['country'] = $req->country;
        $userinfo['last_name'] = $req->last_name;
        $userinfo['phone'] = $req->phone;
        $userinfo['user_type'] = $req->user_type;


        $status = authenticationModel::regCheck($userinfo);
        
        if($status=='error'){
           $user['msg'] = 'email already registered';
           return view('authentication/registration' , $user);
        }else{
            $user['msg'] = 'success';
           return view('authentication/registration' , $user);
        }

        



        //return view('authentication/registration' , $user);

    }

}
