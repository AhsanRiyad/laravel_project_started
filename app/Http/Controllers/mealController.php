<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mealController extends Controller
{
    //

    function addBazar(Request $req){
    	return view('test');
    }


    function test(Request $req){


	//print_r($req->all());

	//return $req->test_input;

    return $req->due_date;

	
	}

}
