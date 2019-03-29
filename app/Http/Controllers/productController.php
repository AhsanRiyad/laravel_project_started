<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;

class productController extends Controller
{
  public function getProducts(Request $req){
   $products = productModel::getAllProducts();
   $clientIP = request()->ip();
   $recommendProducts = productModel::getRecProducts($clientIP);

   if($req->session()->has('userinfo')){
    $loginStatus = true;

    $userinfo = session('userinfo');
    //print_r($userinfo);
    $userinfo2 = json_decode(json_encode($userinfo), true);
    //print_r($userinfo2);

    //echo $userinfo2[0]['u_id'];
    $uid =  $userinfo2[0]['u_id'];

    //for references
    //https://www.geeksforgeeks.org/what-is-stdclass-in-php/
    $c = productModel::cart_count($uid);
    $cart_count = $c[0]->cart_count;
    
    //print_r($c[0]);
    //echo $c[0]->cart_count;


  }else{
    $cart_count = 0;
    $loginStatus = false;
  }

  return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts , 'loginStatus' =>  $loginStatus , 'cart_count' => $cart_count]);

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
