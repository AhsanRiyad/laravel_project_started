<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;
use DB;


/////////////////////////////////////////////
class productController extends Controller
{

  ///////////////////////////////////////////
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

///////////////////////////////////////

////////////////////////////////////
//cart starts
public function cart (Request $req){
  
  $uid = 0;
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

  $results = DB::select("call cartPage(?)" , [$uid]);
  return $results;
}
//cart ends
////////////////////////////////////


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
    //echo $req->myinfo; 
    $t = json_decode($req->myinfo , true);
    //echo $t;

    //echo $t['uid'];
    //echo $t['pid'];
    //echo $t['qntity'];

    $cartInfo['uid'] = $t['uid'];
    $cartInfo['pid'] = $t['pid'];
    $cartInfo['qntity'] = $t['qntity'];

    $info = productModel::addToCart($cartInfo);

    $c = productModel::cart_count($t['uid']);
    $cart_count = $c[0]->cart_count;

    $i = ['cartCount'=> $cart_count , 'status' => $info ];
    $res = json_encode($i);

    echo $res;
    


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



  


  public static function autosearch(Request $req){

    //$h = ['ih' , 'ihoa' , $req->term , $req->cat];
    //$j = json_encode($h);

    $searchDetails = ['searchText' => $req->term , 'category' => $req->cat];
    //$searchDetails = ['searchText' => 'ram' , 'category' => 'all'];



    $results = productModel::autosearch($searchDetails);
    //$results = ['riyad' , 'hellow' , 'hi' , 'riyad'];


    if($results!=[]){
      //multiple element to single element json conversion
          $abc = [];
      for($i = 0 ; $i<sizeof($results); $i++)
      {
        //console.log(i);
        //console.log(result[i].product_name);
        $abc['product'.$i] = $results[$i]->product_name;
      }

      //echo sizeof($results);
    $j = json_encode($abc);

    //print_r($j);
    //print_r($results);
    //print_r($abc);

    echo $j;

    //echo $results[0]->product_name;
    }else{
      $abc = ['results' => 'no results'];
      $j = json_encode($abc);
      echo $j;
    }


  }


   public static function searchProducts(Request $req){

    //$searchDetails = ['searchText' => $text , 'category' => $cat];

    if($req->searchbox==''){
      $req->searchbox = 'nothing';
    }

    $searchDetails = ['searchText' => $req->searchbox , 'category' => $req->catValue];
    $products = productModel::searchProducts($searchDetails);

    //return $products;

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



    return view('product\productSearch', [ 'products' => $products ,  'loginStatus' =>  $loginStatus , 'cart_count' => $cart_count]);
    


   }






}
