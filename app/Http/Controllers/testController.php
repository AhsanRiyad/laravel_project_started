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

		$results = session('hi');
		$resultArray = json_decode(json_encode($results), true);
		return $resultArray[0]['last_name'];
		//print_r($tb);
	}




}
