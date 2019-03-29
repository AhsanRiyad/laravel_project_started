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
 $uid = '';

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
    $uid =  null;

    $cart_count = 0;
    $loginStatus = false;
  }






 return view('product/productdetails' , [ 'products' => $products , 'reviews' =>  $reviews , 'cart_count' => $cart_count , 'loginStatus' => $loginStatus , 'uid' => $uid , 'pid' => $pid]);

}

  public static function addtocart(Request $req){
    echo $req->myinfo; 
    $t = json_decode($req->myinfo , true);
    //echo $t;

    //echo $t['uid'];
    //echo $t['pid'];
    //echo $t['qntity'];

  }

  public static function postReview(Request $req){

    //var sql = "call review("+revInfo.user_id+" , "+revInfo.productId+" , '"+revInfo.rev_text+"' , '"+revInfo.rev_date+"');";

    $rev_date = date('Y-m-d');
    $revInfo['rev_text'] = $req->rev_text;
    $revInfo['rev_date'] = $rev_date;
    $revInfo['productId'] = $req->productid;
    $revInfo['user_id'] = $req->uid;


    $status = productModel::postReview($revInfo);

    return redirect()->route('product.details' , [$req->productid]);

  }




}
