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

        //$results = DB::select('select * from user');
    	//session(['hi' => $results]);
    	//$users = 'hi';

        $pdo = DB::connection('mysql')->getPdo();
        $stmt = $pdo->prepare("CALL test(?)");
        $stmt->bindParam(1, $return_value, \PDO::PARAM_STR, 4000); 

        // call the stored procedure
        $stmt->execute();
        $result[] =  $stmt->fetchAll(\PDO::FETCH_OBJ);
        //print "procedure returned $return_value\n";
        print_r($result);

        //$pdo = DB::connection('mysql')->getPdo();
        //$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        //$stmt = $pdo->prepare('call test(?)');
        //$stmt = $pdo->prepare('call test(?)');
        //$stmt->bindParam(1, $return_value, \PDO::PARAM_STR, 4000); 
       // $stmt->execute();

        return $return_value;

    	//return $results;


    }

}
