<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class mealController extends Controller
{
    //

    function addBazar(Request $req){
    	return view('test');
    }


    function test(Request $req){


	//print_r($req->all());

	//return $req->test_input;
    //return $req->date;

    //$obj = json_decode($req->bazar_details);

    //print_r($req->bazar_details);

    for($i=0 ; $i<count($req->bazar_details); $i++)
    {
        $bazar_name = $req->bazar_details[$i]['bazar_name'] ; 

         $bazar_price = $req->bazar_details[$i]['bazar_price'] ;  

         $bazar_type = $req->bazar_details[$i]['row'] ; 
         $adding_date = date("d-m-Y");

         $bazar_date = $req->date;

        //DB::insert('INSERT INTO `bazar`(`name`, `taka`, `bazar_date`, `adding_date`, `type` , `user`) VALUES (? , ? , ? , ? , ? , `riyad`) ,' , [ $bazar_name , $bazar_price , $bazar_date , $adding_date ,  $bazar_type ]);

        DB::insert(' INSERT INTO bazar (name, taka, bazar_date, adding_date, type , user) VALUES ("regr" , "10.0" ,  "12-12-19" , "12-12-19" , "bazar"  , "1")  ');
        
    }


    

    //var_dump($req);


    //return $req->bazar_details[0]['bazar_name'];
    return count($req->bazar_details);

	
	}

}
