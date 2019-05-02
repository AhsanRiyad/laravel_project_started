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





    //return redirect()->route('a_pos.index');

  }else{
    $cart_count = 0;
    $loginStatus = false;

    //return redirect()->route('authentication.login');

  }

  return view('index' , [ 'products' => $products , 'recommendProducts' =>  $recommendProducts , 'loginStatus' =>  $loginStatus , 'cart_count' => $cart_count]);

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

  
  $params = [$uid];
  //return $params;
  $results = multipleSelectModel::CallRaw('cartPage', $params);

  //$results = DB::select('call CartPage(?)' , [$uid]);

  //return $results;

  // return $results;
 // return $results[0][0]->product_id;
  $r = [ 'products'=> $results , 'cart_count' => $cart_count , 'loginStatus' => $loginStatus ];


  $pdf = PDF::loadView('email.orderConfirm', $r)->save('pdf/confirm.pdf');

  return view('product.cart'  , $r);













}
//cart ends
////////////////////////////////////




public function add_product (Request $req){
  
  if($req->session()->has('userinfo')){
      $userinfo1 = session('userinfo');
      $userinfo2 = json_decode(json_encode($userinfo1), true);

      $userinfo['userinfo'] = $userinfo2;

     
      $products = DB::table('products')->paginate(10);



        //return $revenue;


      //return $userinfo[0]['u_id'];
        return view('product.addProduct')->withMsg('')->withUserinfo($userinfo2)->with('page_name' , 'add_products')->with('products' , $products);      }
        else{
        return redirect()->route('authenticationController.logout');
      }




}

public function add_productPost (Request $req){
  
  if($req->session()->has('userinfo')){
      $userinfo1 = session('userinfo');
      $userinfo2 = json_decode(json_encode($userinfo1), true);

      $userinfo['userinfo'] = $userinfo2;

     
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
        else{
        return redirect()->route('authenticationController.logout');
      }




}



public function up_rev (Request $req){

 
$products = DB::table('products')->paginate(10);


if($req->session()->has('userinfo')){
      $userinfo1 = session('userinfo');
      $userinfo2 = json_decode(json_encode($userinfo1), true);

      $userinfo['userinfo'] = $userinfo2;

     
      $products = DB::table('products')->paginate(10);



        //return $revenue;


      //return $userinfo[0]['u_id'];
        return view('product.viewproducts')->withMsg('')->withUserinfo($userinfo2)->with('page_name' , 'up_rev')->with('products' , $products);      }
        else{
        return redirect()->route('authenticationController.logout');
      }






}



public function view_review (Request $req , $id){

 



if($req->session()->has('userinfo')){
      $userinfo1 = session('userinfo');
      $userinfo2 = json_decode(json_encode($userinfo1), true);

      $userinfo['userinfo'] = $userinfo2;


      $review = DB::table('review')->where('product_id' , $id)->paginate(10);

        //return $revenue;


      //return $userinfo[0]['u_id'];
        return view('product.view_review')->withMsg('')->withUserinfo($userinfo2)->with('page_name' , 'up_rev')->with('review' , $review);      }
        else{
        return redirect()->route('authenticationController.logout');
      }






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


public function categorySearch(Request $req ,  $catName , $subCatName){



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


$products = DB::select('select * from products where category_name = (?) and sub_category = (?)' , [$catName , $subCatName]);


return view('product.product_cat')->with('catName' , $catName)->with('subCat' , $subCatName)->with('searchResult' , $products)->with('loginStatus' , $loginStatus)->with('cart_count' , $cart_count);

}



public function confirmOrder(Request $req){

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

  
  $params = [$uid];
  $results = multipleSelectModel::CallRaw('cartPage', $params);

  // return $results;
 // return $results[0][0]->product_id;



  $r = [ 'products'=> $results , 'cart_count' => $cart_count , 'loginStatus' => $loginStatus ];
  return view('Order.confirm_order'  , $r);


}








public function confirmOrderPost(Request $req){

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
     

    $params = [ $uid , $req->optradio ];

    $results = multipleSelectModel::CallRaw('order_t', $params);




  }else{
    $cart_count = 0;
    $loginStatus = false;
  }

  
  // $pdf = PDF::loadView('email.orderConfirm', $data)->save('pdf/confirm.pdf');
  //return $pdf->download('invoice.pdf');
  
  //return  $userinfo2[0]['u_email'];


   $receiverEmail = $userinfo2[0]['u_email'];
 $receiverName = $userinfo2[0]['last_name'];





  //$params = [$uid];
  //$email = new MailController();
  //$email->basic_email();
  //$email->attachment_email($receiverName , $receiverEmail);
  //unset($email);

  $data = array('name'=>$receiverName);
   Mail::send(['text'=>'email.plain_text'], $data, function($message) {

        $userinfo = session('userinfo');
        //print_r($userinfo);
        $userinfo2 = json_decode(json_encode($userinfo), true);
        $receiverEmail = $userinfo2[0]['u_email'];
        $receiverName = $userinfo2[0]['last_name'];
         $message->to($receiverEmail, $receiverName)->subject
            ('Umart Shopping Invoice');
         $message->attach(public_path().'/pdf/confirm.pdf');
         //$message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
         $message->from('riyad.for.test@gmail.com','Ahsan Riyad');
      });
 
  // return $results;
 // return $results[0][0]->product_id;



  $r = [  'cart_count' => $cart_count , 'loginStatus' => $loginStatus ];
  return view('Order.done'  , $r);


}




public function all_products(Request $req){

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

  
  $params = [$uid];
  $results = DB::table('products')->orderBy('product_id' , 'desc')->paginate(18);

  // return $results;
 // return $results[0][0]->product_id;
  $r = [ 'searchResult'=> $results , 'cart_count' => $cart_count , 'loginStatus' => $loginStatus ];
  return view('product.all_products'  , $r);


}





public function delete_review (Request $req , $id){

 



if($req->session()->has('userinfo')){
      $userinfo1 = session('userinfo');
      $userinfo2 = json_decode(json_encode($userinfo1), true);

      $userinfo['userinfo'] = $userinfo2;


      $review = DB::table('review')->where('product_id' , $req->product_id)->paginate(10);

        //return $revenue;

      $status = DB::delete('delete from review where review_id = (?)' , [$id]);



      //return $userinfo[0]['u_id'];
        return redirect()->route('product.view_review' , [$req->product_id])->withMsgfls('Review Deleted');      }
        else{
        return redirect()->route('authenticationController.logout');
      }






}









}
