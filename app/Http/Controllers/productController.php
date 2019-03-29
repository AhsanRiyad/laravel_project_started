<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;

class productController extends Controller
{
    public function getProducts(){
    	$products = productModel::getAllProducts();
    	$clientIP = request()->ip();
    	$recommendProducts = productModel::getRecProducts($clientIP);
      return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts  ]);

    	//return $clientIP;

  }

  public function getProductDetails(Request $req, $pid){
   $products = productModel::getProductDetails($pid);


        //visit table works
   $clientIP = $req->ip();
   //echo $clientIP;
   $visitTable = ['ip'=> $clientIP , 'productid'=> $pid];
   //$visitTable = array('ip'->$clientIP , 'productid'->$pid);
   productModel::recommendProduct($visitTable);



        //gets reviews
   $reviews = productModel::getReviews($pid);

    	//$pAndr = [ 'products' => $products , 'reviews' =>  $reviews  ];

    	//return $products[0]->product_name;
   return view('product/productdetails' , [ 'products' => $products , 'reviews' =>  $reviews  ]);

}


}
