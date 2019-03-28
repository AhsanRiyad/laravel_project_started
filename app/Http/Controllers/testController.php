<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\testModel;
use DB;

class testController extends Controller
{
    //

	public function dbTest(){
		//$tb = DB::select('select * from user');
		$tb = testModel::testDB();


		 return $tb[1];
		//print_r($tb);
	}




}
