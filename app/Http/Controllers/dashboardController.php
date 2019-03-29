<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dashboardModel;

class dashboardController extends Controller
{
    public static function dashboard(){
    	$userinfo1 = session('userinfo');
    	$userinfo2 = json_decode(json_encode($userinfo1), true);

    	$userinfo['userinfo'] = $userinfo2;


    	//return $userinfo[0]['u_id'];
    	return view('dashboard/dashboard' , $userinfo);
    }
}
