<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\authenticationModel;

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
        $user['validCheck'] = true; 
        return view('authentication/registration' , $user);

    }

    public static function signUpCheck(Request $req){

        $user = ['msg' => 'Welcome, Create your Umart Account' , 'loginStatus' => false] ; 
        $user['validCheck'] = true; 
        return view('authentication/registration' , $user);

    }

}
