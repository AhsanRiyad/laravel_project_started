<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;

class testModel extends Model
{
    public static function testDB(){

    	//$users = DB::select('call userTest');
    	$users = DB::select('call userTest');
    	return $users;


    }

}
