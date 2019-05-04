<?php

namespace App;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use App\multipleSelectModel;
use DB;

class productModel extends Model
{
    public static function getAllProducts(){
    	$products = DB::select('select * from products');
    	return $products;
    }
    public static function getRecProducts($userip){
    	$products = DB::select("select products.* from visit , products where visit.product_id = products.product_id and user_ip=(?)
" , [$userip]);
    	return $products;
    }

    public static function getProductDetails($pid){
    	$products = DB::select('select * from products where product_id =(?)' , [$pid]);
    	return $products;
    }


    public static function getReviews($pid){
    	$reviews = DB::select('select r.* , u.last_name from review r , user u where r.user_id=u.u_id and product_id =(?)' , [$pid]);
    	return $reviews;
    }

    public static function postReview($revInfo){
        
        //$status = "call review("+revInfo.user_id+" , "+revInfo.productId+" , '"+revInfo.rev_text+"' , '"+revInfo.rev_date+"');";
        

        try{
        $status = DB::select("call review(? , ? , ? , ? , ?)" , [$revInfo['user_id'] , $revInfo['productId'] , $revInfo['rev_text'] , $revInfo['rev_date'] , $revInfo['rating']] );

            return 'success';
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }





    }




    public static function recommendProduct($visitTable){

		//var sql = "select * from visit where product_id="+visitTable.productid+" and user_ip='"+visitTable.ip+"'";
		//var sql = "select * from visit where product_id=(?) and user_ip=(?)";

		$results = DB::select("select * from visit where product_id=(?) and user_ip=(?)", [$visitTable['productid'] , $visitTable['ip']]);

		if($results==[])
		{
			$status = DB::insert( "INSERT INTO `visit`(`product_id`,`user_ip`) VALUES (? , ?)" , [$visitTable['productid'] , $visitTable['ip']] );
		}

    	
    }


    public static function cart_count($uid){
        //var sql = "SELECT COUNT(*) as cart_count FROM `cart` WHERE user_id = "+uid+";"
        $status = DB::select("SELECT COUNT(*) as cart_count FROM `cart` WHERE user_id =(?)" , [$uid]);

        return $status;

    }

    public static function addToCart($cartInfo){
        //var sql = "SELECT COUNT(*) as cart_count FROM `cart` WHERE user_id = "+uid+";"
        //var sql = "call cart("+Number(info.pid)+" , "+Number(info.uid)+" , "+Number(info.qntity)+" );";

        


        try{
            $params = [ $cartInfo['pid'] , $cartInfo['uid'] ,  $cartInfo['qntity'] ];
        $status = multipleSelectModel::CallRaw('cart', $params);
        //$status = DB::statement("call cart( ?, ?  , ?  )" , [ $cartInfo['pid'] , $cartInfo['uid'] ,  $cartInfo['qntity'] ] );

            return 'success';
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }

        

    }
 
    
  
    public static function autosearch($searchDetails){
        
        $pname ='%' . $searchDetails['searchText'] . '%' ;
        $catName = $searchDetails['category'] ;

 
        try{
        if($catName == 'all'){
            $results = DB::select( "select `product_name` from products where product_name like (?) " , [$pname]);

            return $results;

        }else{
            $results = DB::select("select `product_name` from products where product_name like (?) and category_name = (?) ", [$pname , $catName] );
            return $results;
        }   
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }

        

    }



     public static function searchProducts($searchDetails){
        
        $pname ='%' . $searchDetails['searchText'] . '%' ;
        $catName = $searchDetails['category'] ;

 
        try{
        if($catName == 'all'){

            
            $results = DB::select( "select * from products where product_name like (?) " , [$pname]);

            return $results;

        }else{
            $results = DB::select("select * from products where product_name like (?) and category_name = (?) ", [$pname , $catName] );
            return $results;
        }   
        }catch(QueryException $ex){ 
            //$msg = $ex->getMessage(); 
            return 'error'; 

        }

        

    }


}
