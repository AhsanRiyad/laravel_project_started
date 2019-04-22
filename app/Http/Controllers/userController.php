<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\multipleSelectModel;
Use Exception;

class userController extends Controller
{

    public function addUser(Request $req){


     //return $req;
        //return $userinfo[0]['u_id'];
        return view('user.addUser')->withMsg('')->withUserinfo($req->userinfo)->with('page_name' , 'addUser');
        //return view('dashboard/dashboard' , $userinfo);
        

    } 


    public function addUserPost(Request $req){


        $status = 'successful' ;
        $Validation = Validator::make($req->all() , [
            'name'=>'required|between:3,20',
            'email'=>'required|email',
            'password'=>'required|between:6,20',
            'type'=>'required'
        ]);

         $Validation->Validate();



        try {

            $status = DB::insert("INSERT INTO `user`(`u_password`, `u_email` , `u_type`,`last_name`) VALUES (?,?,?,?)" , [$req->password , $req->email , $req->type , $req->name ] );

            $msg = 'Registration Successful';
            //return $status;
        //return $req->password;
        //return $userinfo[0]['u_id'];

            //throw new Exception("Duplicate Email", 1);

            return view('user.addUser')->withMsg($msg)->withUserinfo($req->userinfo)->with('page_name' , 'addUser');


        } catch (Exception $e) {
            //report($e);

         $msg = 'Failed! Duplicate Email';
            //return $status;
        //return $req->password;
        //return $userinfo[0]['u_id'];
         return view('user.addUser')->withMsg($msg)->withUserinfo($req->userinfo)->with('page_name' , 'addUser');
     }





        //return view('dashboard/dashboard' , $userinfo);


}

public function add_factory(Request $req){



    return view('product.addFactory')->withMsg('')->withUserinfo($req->userinfo)->with('page_name' , 'add_factory');
    
}







public function add_factoryPost(Request $req){






    $Validation = Validator::make($req->all() , [
        'name'=>'required|between:3,20',
        'location'=>'required|between:3,20'
    ]);

    $Validation->validate();


    try {


            $status = DB::insert("INSERT INTO `factory`(`name`, `location`) VALUES (?,?)" , [$req->name , $req->location ] );

            $msg = 'Registration Successful';

            return view('product.addFactory')->withMsg($msg)->withUserinfo($req->userinfo)->with('page_name' , 'add_factory');


        } catch (Exception $e) {

            $msg = 'Failed! Database Exception';

            return view('product.addFactory')->withMsg($msg)->withUserinfo($req->userinfo)->with('page_name' , 'add_factory');


        }

    


}



public function ship_req_india(Request $req){



    try {
        $ship_reqs = DB::select('select s.* , u.last_name from shipment s , user u   where u.u_id = s.admin_id_req  and  s.status = 0  order by id desc');

    // return $ship_reqs;
    //dd($ship_reqs);

        //return $userinfo[0]['u_id'];
    return view('product.ship_req_india')->withMsg('none')->withUserinfo($req->userinfo)->with('ship_reqs' , $ship_reqs )->with('page_name' , 'ship_req_india');
        
    }
    catch (Exception $e) 
    {
        return view('product.ship_req_india')->withMsg('none')->withUserinfo($req->userinfo)->with('ship_reqs' , $ship_reqs )->with('page_name' , 'ship_req_india');
    }

    
        //return view('dashboard/dashboard' , $userinfo);

}


public function ship_req_bd(Request $req){




        
        try {


            $products = DB::select('select * from products');

        $users = DB::select("select * from user where u_type = 'user' ");
        //return $results;

        



//return $req->userinfo[0]['u_id'];


        $productList = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$req->userinfo[0]['u_id']]);


        
        //return $userinfo['userinfo'];
        //return $userinfo1[0]->u_id;
        //return $userinfo[0]['u_id'];
        

        return view('product.ship_req_bd')->withProducts($products)->withUsers($users)->withUserinfo($req->userinfo)->with('productList' , $productList)->with('page_name' , 'ship_req_bd');
            
        } catch (Exception $e) {
            

            return 'Database Exception';

        }


        








        //return view('dashboard/dashboard' , $userinfo);
        
    
        //return view('dashboard/dashboard' , $userinfo);
    

}


public function ship_req_bd_post(Request $req){

    //echo 'hellow';
    //echo $req->myinfo;


    try {


        $t = json_decode($req->myinfo);
    //echo $t->uid;


    $status = DB::statement('call shipment_cart(?,?,?)',[$t->uid , $t->pid , $t->qntity]);



    $products = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$t->uid]);






    return json_encode($products);
        
    } catch (Exception $e) {
        

        return 'Exception Occured';

    }

    






}




public function a_shipment_reset($uid){

    //echo $uid;
    //echo 'hellow';


    $status = DB::delete('delete from shipment_temp where admin_id = (?)', [$uid]);



}


public function a_shipment_details($ship_id){

    //echo $uid;
    // echo 'hellow';

    $results = DB::select('select s.* , p.product_name from shipment_product s , products p where s.product_id = p.product_id and shipment_id = (?)' , [$ship_id]);

    return $results;
    



}




public function ship_accept($admin_id , $ship_id){

    //echo $uid;
    // echo 'hellow';

    //$results = DB::select('select s.* , p.product_name from shipment_product s , products p where s.product_id = p.product_id and shipment_id = (?)' , [$ship_id]);

    // return $results;
    // echo 'hellow'+$admin_id+' '+$ship_id;
    // echo $admin_id;

    $status = DB::update('update shipment set status = 1 ,  acc_date = SYSDATE() , admin_id_acc = (?)  where id = (?) ' , [$ship_id , $admin_id]);


    $ship_reqs = DB::select('select s.* , u.last_name from shipment s , user u where u.u_id = s.admin_id_req and s.status = 0');


    $counter = DB::select('select count(*) as c from shipment where status = 0');
    $sta = DB::statement('call shipment_to_products(?)' , [$ship_id]);


    $results = [$ship_reqs , $counter];
    return $results;



}


public function ship_reject($admin_id , $ship_id){

    //echo $uid;
    // echo 'hellow';

    //$results = DB::select('select s.* , p.product_name from shipment_product s , products p where s.product_id = p.product_id and shipment_id = (?)' , [$ship_id]);

    // return $results;
    // echo 'hellow'+$admin_id+' '+$ship_id;
    // echo $admin_id;

    $status = DB::update('update shipment set status = 2 ,  acc_date = SYSDATE() , admin_id_acc = (?)  where id = (?) ' , [$ship_id , $admin_id]);


    $ship_reqs = DB::select('select s.* , u.last_name from shipment s , user u   where u.u_id = s.admin_id_req  and  s.status = 0 ');


    
    $counter = DB::select('select count(*) as c from shipment where status = 0');

    $results = [$ship_reqs , $counter];
    return $results;

}



public function a_shipment_request($uid){

    //echo $uid;
    //echo 'hellow';


    //$status = DB::delete('delete from shipment_temp where admin_id = (?)', [$uid]);
     //echo 'hellow';


    //$status = DB::select(DB::raw("CALL shipment_req(?)" , [$uid]));

 try {

    $count = DB::select('select count(*) as c from shipment_temp where admin_id = (?)' , [$uid]);


    if($count[0]->c ==0){

        throw new Exception("No Product Found", 1);

    }else{

        $results = multipleSelectModel::CallRaw('shipment_req',  [$uid]);

    }



} catch (Exception $e) {
        //report($e);

        //return $e->getMessage;
    return false;
}


}




public function add_raw_materials(Request $req){

    //return 'hellow';

        $rawMaterials = DB::select('select * from raw_materials');

        $factories = DB::select('select * from factory');


        //return $userinfo[0]['u_id'];
        return view('product.add_raw_materials')->withMsg('')->withUserinfo($req->userinfo)->with('rawMaterials' , $rawMaterials)->with('factories' , $factories)->with('page_name' , 'add_raw_materials');
        //return view('dashboard/dashboard' , $userinfo);
    
}

public function add_raw_materialsPost(Request $req){


    
        $factory_id = $req->factory_name;
        $materials_id = $req->materials;
        $qntity = $req->quantity;



        $Validation = Validator::make($req->all() , [
            'factory_name'=>'required|between:1,20',
            'materials'=>'required|between:1,10',
            'quantity'=>'required|between:1,10',
        
        ]);

         $Validation->Validate();



        $status = DB::insert('INSERT INTO `factory_materials`(`factory_id`, `materials_id`, `qntity`) VALUES (? ,? , ?)' , [$factory_id , $materials_id , $qntity]);


        $rawMaterials = DB::select('select * from raw_materials');

        $factories = DB::select('select * from factory');


        //return $userinfo[0]['u_id'];
        return view('product.add_raw_materials')->withMsg('Successful')->withUserinfo($req->userinfo)->with('rawMaterials' , $rawMaterials)->with('factories' , $factories)->with('page_name' , 'add_raw_materials');
        //return view('dashboard/dashboard' , $userinfo);
    




}





}
