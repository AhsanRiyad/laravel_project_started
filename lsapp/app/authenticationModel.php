<?php

namespace App;

use Illuminate\Database\QueryException;
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
	public static function regCheck($user){
		$dob = $user['year'].'-'.$user['month'].'-'.$user['day'];



    	//var  sql = "INSERT INTO `user`(`u_password`, `u_email`, `u_mobile`, `dob`, `u_status`, `u_type`, `first_name`, `last_name`) VALUES ('"+user.password+"','"+user.email+"',"+user.phone+",'"+dob+"','valid','"+user.user_type+"','"+user.first_name+"','"+user.last_name+"')";


		$msg = '' ; 


		try { 
     	$sql = DB::insert("INSERT INTO `user`(`u_password`, `u_email`, `u_mobile`,  `u_status`, `u_type`,  `last_name`) VALUES (?,?,?,'valid','admin',?)" , [$user['password'],$user['email'],$user['phone'],$user['last_name']]);

     		 return 'success';
     		//return $user['country']; 

     		//return $user;

		} catch(QueryException $ex){ 
			//$msg = $ex->getMessage(); 
     		return 'error'; 

		}


		
		//return $msg;


	}
}
