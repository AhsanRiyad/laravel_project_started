<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Http\Request;
use App\multipleSelectModel;

class testModel extends Model
{
    public static function testDB(){
    	
    	
    	//$users = DB::select('call userTest');
    	//$users = DB::select("call test1");

        //$results = multipleSelectModel::CallRaw('spGetData',$params);
        //$params = [];
        //$results = multipleSelectModel::CallRaw('test1', $params);
        //$users = multipleSelectModel::select("call test1");

    	//$age = array("Peter"=>"35", "Ben"=>"37", "Joe"=>"43");

        $results = DB::select('select * from user');
    	session(['hi' => $results]);
    	//$users = 'hi';

    	return $results;


    }

}
