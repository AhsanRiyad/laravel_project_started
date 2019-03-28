<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;

class productController extends Controller
{
    public function getProducts(){
    	$products = productModel::getAllProducts();
    	$clientIP = request()->ip();
    	$recommendProducts = productModel::getRecProducts('::1');
    	 return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts  ]);

    	//return $clientIP;
    	
    }

    public function getProductDetails($pid){
    	$products = productModel::getProductDetails($pid);

    	$reviews = productModel::getReviews($pid);
    	
    	//return $reviews;
    	return view('product/productdetails' , $products);

    }


}
