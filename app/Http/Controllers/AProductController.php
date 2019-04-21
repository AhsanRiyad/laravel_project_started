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
    public function index(Request $req)
    {
        //
        $products = DB::select('select * from products');

        $users = DB::select("select * from user where u_type = 'user' ");
        //return $results;

       

        //return $userinfo1[0]->u_id;
        //return $userinfo[0]['u_id'];
        return view('product.a_pos')->withProducts($products)->withUsers($users)->withUserinfo($req->userinfo)->with('page_name' , 'a_pos');
        //return view('dashboard/dashboard' , $userinfo);
        




        
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

public function req_countPost(){

    
    $reqCount = DB::select('select count(*) as c from shipment where status =0'); 



    $moneyReq =  DB::select('select count(*) as c from money_transfer where status =0'); 

   // $i = [ 'cart_products' => $reqCount ];

//res = json_encode($i);

    //echo $reqCount;
   // echo 'helo';

    return [$reqCount, $moneyReq];

   // return 'hellow';

  }



public function order_details($id){

    //echo $uid;
    // echo 'hellow';

    $results = DB::select('select o.* , p.product_name , p.product_sell_price from order_includ_product  o, products p where o.product_id = p.product_id and order_id = (?)' , [$id]);

    return $results;
    



}



  public function a_cart_order(Request $req){

    //echo $req->myinfo;

    $t = json_decode($req->myinfo , true);

    /*$cartInfo['uid'] = $t['uid'];
    $cartInfo['total'] = $t['total'];
    $cartInfo['paid'] = $t['paid'];
    $cartInfo['salesPoint'] = $t['salesPoint'];*/

    //$sql =  "call a_order_t(".$t['uid']." , ".$t['total']." , ".$t['paid']." , '".$t['salesPoint']."')";
    //$status = DB::select($sql);
    //return $sql;
    //echo 'hi';
    //echo 'hello';
    //$userinfo1 = session('userinfo');
    //var_dump($userinfo);
    //return $userinfo1['u_id'];
    //return $userinfo1->u_id;




    if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;
        //return $userinfo1[0]->u_id;

        //return $userinfo['u_id'];
        //return 'hellow';
        
        try{
       $status = DB::statement("call a_order_t( ?, ?  , ? , ? , ? )" , [ $t['uid'] , $t['total'] ,  $t['paid'] , $t['salesPoint'] , $userinfo1[0]->u_id  ] );

            return 'success';
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }




        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }






    /*try{
       $status = DB::statement("call a_order_t( ?, ?  , ? , ? , ? )" , [ $t['uid'] , $t['total'] ,  $t['paid'] , $t['salesPoint'] , $userinfo1['u_id']  ] );

            return 'success';
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }
*/

    //echo json_encode($t);
    //echo $t['uid'].' '.$t['paid'].' '.$t['total'].' '.$t['salesPoint'];

    //echo $sql;








}


public function delete_it_ship($ship_id , $admin_id){



    //echo 'hellow';

    $status = DB::delete('delete from shipment_temp where id =(?)' , [$ship_id]);

    return $productList = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$admin_id]);



    //return json_encode($productList);



}

public function update_it_ship($ship_id , $admin_id , $qntity){



    //echo 'hellow';

    $status = DB::update('update shipment_temp set product_quantity = (?) where id =(?)' , [ $qntity , $ship_id]);

    return $productList = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$admin_id]);



    //return json_encode($productList);



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
