<?php

namespace App\Http\Controllers;

use App\a_product;
use Illuminate\Http\Request;
use DB;
use App\productModel;

class AProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = DB::select('select * from products');

        $users = DB::select("select * from user where u_type = 'user' ");
        //return $results;
        return view('product.a_sell_product')->withProducts($products)->withUsers($users);
    }

    public static function addtocart(Request $req){
    //echo $req->myinfo; 
    $t = json_decode($req->myinfo , true);
    //$res = json_encode($t);
    //echo $res;

    //echo 'hello';

    //echo $t['uid'];
    //echo $t['pid'];
    //echo $t['qntity'];

    $cartInfo['uid'] = $t['uid'];
    $cartInfo['pid'] = $t['pid'];
    $cartInfo['qntity'] = $t['qntity'];

    $info = productModel::addToCart($cartInfo);

    $c = productModel::cart_count($t['uid']);
    
    $cartProducts = DB::select('select * from cart c , products p where  p.product_id=c.product_id and user_id = (?)', [$t['uid']] ); 

    $cart_count = $c[0]->cart_count;



   // $i = ['cartCount'=> $cart_count , 'status' => $info , 'cart_products' => $cartProducts ];

    $i = [ 'cart_products' => $cartProducts ];

    $res = json_encode($i);

    echo $res;
  }


  public function a_cart_delete($cart_id , $user_id){

    //echo $id;

    $status = DB::delete('delete from cart where cart_id=(?)', [$cart_id]);

    $cartProducts = DB::select('select * from cart c , products p where  p.product_id=c.product_id and user_id = (?)', [$user_id] ); 

    $i = [ 'cart_products' => $cartProducts ];

    $res = json_encode($i);

    echo $res;

    //echo $cart_id . $user_id . 'from response' ;





  }



  public function a_cart_update($cart_id , $user_id , $qntity){

    //echo $id;

    $status = DB::update('update cart set quantity = (?) where cart_id=(?)', [ $qntity ,$cart_id]);

    $cartProducts = DB::select('select * from cart c , products p where  p.product_id=c.product_id and user_id = (?)', [$user_id] ); 

    $i = [ 'cart_products' => $cartProducts ];

    $res = json_encode($i);

    echo $res;

    //echo $cart_id . $user_id . 'from response' ;





  }



  public function a_cart_reset($user_id){

    $status  = DB::delete('delete from cart where user_id = (?)' , [$user_id]);

    echo 'success';


  }



  public function a_cart_show($user_id){

    $cartProducts = DB::select('select * from cart c , products p where  p.product_id=c.product_id and user_id = (?)', [$user_id] ); 

    $i = [ 'cart_products' => $cartProducts ];

    $res = json_encode($i);

    echo $res;


  }


  public function a_cart_order(Request $req){

    echo $req->myinfo;




  }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\a_product  $a_product
     * @return \Illuminate\Http\Response
     */
    public function show(a_product $a_product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\a_product  $a_product
     * @return \Illuminate\Http\Response
     */
    public function edit(a_product $a_product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\a_product  $a_product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, a_product $a_product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\a_product  $a_product
     * @return \Illuminate\Http\Response
     */
    public function destroy(a_product $a_product)
    {
        //
    }
}
