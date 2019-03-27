<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;

class testModel extends Model
{
    public static function testDB(){
    	
    	
    	//$users = DB::select('call userTest');
    	$users = DB::select('call test1');

    	$age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");

    	session(['hi' => $age]);
    	//$users = 'hi';

    	return $users;


    }

}
