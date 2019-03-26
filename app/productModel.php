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
}
