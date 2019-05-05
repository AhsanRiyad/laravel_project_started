<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\productModel;
use DB;
use App\multipleSelectModel;
use Validator;
use App\Http\Controllers\MailController;
use PDF;
use Mail;

use Carbon\Carbon;

use App\Jobs\sendEmailJob;

/////////////////////////////////////////////
class productController extends Controller
{

  ///////////////////////////////////////////
  public function getProducts(Request $req){
   $products = productModel::getAllProducts();
   $clientIP = request()->ip();
   $recommendProducts = productModel::getRecProducts($clientIP);


return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts , 'loginStatus' =>  $req->s_login_status , 'cart_count' => $req->s_cart_count  ]);

    	//return $clientIP;

}

public function get_reviews(){
  $reviews = DB::select('select * from review');
  return $reviews;
}




///////////////////////////////////////

////////////////////////////////////
//cart starts
public function cart (Request $req){

  $uid = $req->s_uid ;
 
  
  $params = [$uid];
  //return $params;
  //$results = multipleSelectModel::CallRaw('cartPage', $params);

  //$results = DB::select('call CartPage(?)' , [$uid]);


  DB::statement('call cartPage(? , @total)' , [ $uid ]);
  $total = DB::select('select @total as total');
  //return $total[0]->total;

  $products = DB::select('select c.* , p.product_price , p.product_name ,  p.descriptions  from cart c , products p  where c.product_id = p.product_id and user_id = (?)' , [ $uid ]);




  //return $products;

  // return $results;
 // return $results[0][0]->product_id;
  $r = [ 'products'=> $products , 'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status , 'total' => $total[0]->total ];

  

  //$params = [$uid , '@order_id' , '@total' , '@date' ];
  //$invoice_result = multipleSelectModel::CallRaw('order_invoice', $params);
  //return $invoice_result;

  

  return view('product.cart'  , $r);

}
//cart ends
////////////////////////////////////




public function add_product (Request $req){
  
  
     
      $products = DB::table('products')->paginate(10);



        //return $revenue;


      //return $userinfo[0]['u_id'];
        return view('product.addProduct')->withMsg('')->withUserinfo($req->userinfo)->with('page_name' , 'add_products')->with('products' , $products);      



}

public function add_productPost (Request $req){
  

     
      $products = DB::table('products')->paginate(10);

      /*if($req->hasFile('img')){
        $file = $req->file('img');
        echo $file->getClientOriginalName();
        echo '<br/>';
        echo $file->getClientOriginalExtension();
        echo '<br/>';
        echo $file->getSize();
        echo '<br/>';
        echo $file->getRealPath();
        echo '<br/>';
        echo $file->getRealPath();
        echo '<br/>';
        echo $file->getMimeType();
        echo '<br/>';

        $destinationPath = 'uploads';
       $file->move($destinationPath,$file->getClientOriginalName());
       echo '<br/>';

        return $file;
      }
*/

      $Validation = Validator::make($req->all() , [
            'name'=>'required|between:3,20',
            'price'=>'required|integer|between:3,5000',
            'img'=>'required|file|image',
            //'type'=>'required'
        ]);

      $Validation->Validate();


      
      
        $file = $req->file('img');
        


          $destinationFolder = 'uploads';

          $productId = DB::select('select max(product_id) as m from products');
          $pid = $productId[0]->m;
          //return $pid;

          //$imgPath = $destinationPath.'/'.$file->getClientOriginalName();
          
          $justFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

          $imgName = $justFileName.$pid.'.'.$file->getClientOriginalExtension();
          $destinationPath = $destinationFolder.'/'.$imgName;

          $file->move($destinationFolder, $imgName);

          //return $justFileName;

          $status = DB::insert('insert into products (product_name , product_price , image) values (? , ? , ?)', [$req->name , $req->price , $destinationPath]);

            return redirect()->route('productController.add_product')->with('msgfls' , 'Successful');

        

          //return 'true';
       


      

      

      //return $userinfo[0]['u_id'];
             
    




}



public function up_rev (Request $req){

 
$products = DB::table('products')->paginate(10);

     
      $products = DB::table('products')->paginate(10);

        //return $revenue;

      //return $userinfo[0]['u_id'];
        return view('product.viewproducts')->withMsg('')->withUserinfo($req->userinfo)->with('page_name' , 'up_rev')->with('products' , $products);    


}



public function view_review (Request $req , $id){

 




      $review = DB::table('review')->where('product_id' , $id)->paginate(10);

        //return $revenue;


      //return $userinfo[0]['u_id'];
        return view('product.view_review')->withMsg('')->withUserinfo($req->userinfo)->with('page_name' , 'up_rev')->with('review' , $review);    





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
DB::statement('call avgRating(? , @rat)' , [ $pid ]);
$avgRating = DB::select('select @rat as rating');
//return $avgRating;



DB::statement('call userRating(? , ? , @rat);' , [$pid , $req->s_uid ]);
$userRating = DB::select('select @rat as rating');
//return $userRating;


//return $userRating; 
//return $avgRating;

return view('product/productdetails' , [ 'products' => $products , 'reviews' =>  $reviews , 'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status , 'uid' => $req->s_uid , 'pid' => $pid , 'userRating' => $userRating  , 'avgRating' => $avgRating ] );



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
  $revInfo['rating'] = $req->rating;


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

  


 return view('product.productSearch', [ 'products' => $products ,  'loginStatus' =>  $req->s_login_status , 'cart_count' => $req->s_cart_count]);



}


public function categorySearch(Request $req ,  $catName , $subCatName){


$products = DB::select('select * from products where category_name = (?) and sub_category = (?)' , [$catName , $subCatName]);


 return view('product.product_cat', [ 'searchResult' => $products ,  'loginStatus' =>  $req->s_login_status , 'cart_count' => $req->s_cart_count , 'catName' => $catName ,  'subCat' => $subCatName  ]);

}



public function confirmOrder(Request $req){

/*
  
  $params = [$req->s_uid];
  $results = multipleSelectModel::CallRaw('cartPage', $params);*/


  $uid = $req->s_uid ;
 
  
  $params = [$uid];
  //return $params;
  //$results = multipleSelectModel::CallRaw('cartPage', $params);

  //$results = DB::select('call CartPage(?)' , [$uid]);


  DB::statement('call cartPage(? , @total)' , [ $uid ]);
  $total = DB::select('select @total as total');
  //return $total[0]->total;

  $products = DB::select('select c.* , p.product_price , p.product_name ,  p.descriptions  from cart c , products p  where c.product_id = p.product_id and user_id = (?)' , [ $uid ]);




  // return $results;
 // return $results[0][0]->product_id;


  $r = [ 'products'=> $products , 'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status , 'total' => $total[0]->total ];

  return view('Order.confirm_order'  , $r);


}








public function confirmOrderPost(Request $req){

   //sendEmailJob::dispatch($req)->delay(now()->addSeconds(20));
  $data = [ 'uid' => $req->s_uid , 'payment_method' => $req->optradio ];    
  
  $userinfo = session('userinfo');
  $userinfo2 = json_decode(json_encode($userinfo), true);

  $uid =  $req->s_uid ;
  $payment_method = $req->optradio ;
  $receiverEmail = $userinfo2[0]['u_email'];
  $receiverName = $userinfo2[0]['last_name'];


  ///return $receiverName;


   dispatch(new sendEmailJob( $uid ,  $payment_method ,  $receiverEmail , $receiverName ))->delay(Carbon::now()->addSeconds(1));


  $r = [  'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status ];
  return view('Order.done'  , $r);


}




public function all_products(Request $req){

$uid = 0;
  
  $params = [$req->s_uid];
  $results = DB::table('products')->orderBy('product_id' , 'desc')->paginate(18);

  // return $results;
 // return $results[0][0]->product_id;
  $r = [ 'searchResult'=> $results , 'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status ];
  return view('product.all_products'  , $r);


}





public function delete_review (Request $req , $id){


      $review = DB::table('review')->where('product_id' , $req->product_id)->paginate(10);

        //return $revenue;

      $status = DB::delete('delete from review where review_id = (?)' , [$id]);


      //return $userinfo[0]['u_id'];
        return redirect()->route('product.view_review' , [$req->product_id])->withMsgfls('Review Deleted');     



}


///////////////support//////////////////

public function support(Request $req){



 $r = [  'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status , 'name' => $req->s_name , 'email' => $req->s_email ];
  return view('email.support'  , $r);

}

public function supportPost(Request $req){


  return $req->msg;
  
  



  


 $r = [  'cart_count' => $req->s_cart_count , 'loginStatus' => $req->s_login_status , 'name' => $req->s_name , 'email' => $req->s_email ];
  return view('email.support'  , $r);

}

















}
