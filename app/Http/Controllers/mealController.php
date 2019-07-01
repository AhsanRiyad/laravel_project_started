<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\bazar;

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
    $i = 0;
    for($i=0 ; $i<count($req->bazar_details); $i++)
    {
        $bazar_name = $req->bazar_details[$i]['bazar_name'] ; 

         $bazar_price = $req->bazar_details[$i]['bazar_price'] ;  

         $bazar_type = $req->bazar_details[$i]['row'] ; 
         $adding_date = date("Y-m-d");

         $bazar_date = $req->date;

        //DB::insert('INSERT INTO `bazar`(`name`, `taka`, `bazar_date`, `adding_date`, `type` , `user`) VALUES (? , ? , ? , ? , ? , `riyad`) ,' , [ $bazar_name , $bazar_price , $bazar_date , $adding_date ,  $bazar_type ]);
        //return $req->bazar_details[1]['bazar_price'];
         //DB::insert('INSERT INTO bazar (name, taka, bazar_date, adding_date, type , user) VALUES (? , ? ,  ? , ? , ?  , "1")  ' , [ $bazar_name , $bazar_price , $bazar_date, $adding_date , $bazar_type ] );


        $bazar = new bazar;

        $bazar->name = $bazar_name;
        $bazar->taka = $bazar_price;
        $bazar->bazar_date = $bazar_date;
        $bazar->adding_date = $adding_date;
        $bazar->type = $bazar_type;
        $bazar->user = '1';

        $bazar->save();

         //return count($req->bazar_details);
        
    }


    //return $req->date;

    //var_dump($req);
    //return $i;


     

    //return $req->bazar_details[1]['bazar_name'];
    //return count($req->bazar_details);

	
	}

}
