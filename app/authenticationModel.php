<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class authenticationModel extends Model
{
    //
    public static function loginCheck($user){

    	$userDetails = DB::select('select * from user where u_email=(?) and u_password=(?)' , [$user['email'] , $user['password']]);

    	//return $userDetails;
    	return $userDetails;


    }
}
