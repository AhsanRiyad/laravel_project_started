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

    //$obj = json_decode($req->bazar_details);

    //print_r($req->bazar_details);



    return $req->bazar_details[0]['bazar_name'];

	
	}

}
