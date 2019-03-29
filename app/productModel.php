<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class productModel extends Model
{
    public static function getAllProducts(){
    	$products = DB::select('select * from products');
    	return $products;
    }
    public static function getRecProducts($userip){
    	$products = DB::select("select products.* from visit , products where visit.product_id = products.product_id and user_ip=(?)
" , [$userip]);
    	return $products;
    }

    public static function getProductDetails($pid){
    	$products = DB::select('select * from products where product_id =(?)' , [$pid]);
    	return $products;
    }


    public static function getReviews($pid){
    	$reviews = DB::select('select * from review where product_id =(?)' , [$pid]);
    	return $reviews;
    }

    public static function recommendProduct($visitTable){

		//var sql = "select * from visit where product_id="+visitTable.productid+" and user_ip='"+visitTable.ip+"'";
		//var sql = "select * from visit where product_id=(?) and user_ip=(?)";

		$results = DB::select("select * from visit where product_id=(?) and user_ip=(?)", [$visitTable['productid'] , $visitTable['ip']]);

		if($results==[])
		{
			$status = DB::insert( "INSERT INTO `visit`(`product_id`,`user_ip`) VALUES (? , ?)" , [$visitTable['productid'] , $visitTable['ip']] );
		}

    	
    }

    private function insertIp($visitTable){
    	
		//var sql = "INSERT INTO `visit`(`product_id`,`user_ip`) VALUES ("+ip.productid+" , '"+ip.ip+"')";
    	
    	
    	

    }


}
