<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class authenticationController extends Controller
{
    //
    public static function login(Request $req){

    	return view('authentication/login');

    }
}
