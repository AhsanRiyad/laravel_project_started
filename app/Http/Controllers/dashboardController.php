<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dashboardModel;

class dashboardController extends Controller
{
    public static function dashboard(){
    	$userinfo = session('userinfo');
    	return $userinfo;
    	//return view('dashboard/dashboard' , $userinfo);
    }
}
