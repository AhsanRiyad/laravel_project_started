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
    	$products = DB::select("select products.* from visit , products where visit.product_id = products.product_id and user_ip='::1'
");
    	return $products;
    }


}
