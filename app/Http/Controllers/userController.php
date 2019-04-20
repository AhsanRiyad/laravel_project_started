<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\multipleSelectModel;

class userController extends Controller
{

    public function addUser(Request $req){

    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;





        //return $req;
        //return $userinfo[0]['u_id'];
            return view('user.addUser')->withMsg('')->withUserinfo($userinfo2)->with('page_name' , 'addUser');
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    } 


    public function addUserPost(Request $req){



        if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            $status = 'successful' ;
            $Validation = Validator::make($req->all() , [
                'name'=>'required',
                'email'=>'required',
                'password'=>'required',
                'type'=>'required'
            ]);

         //$Validation->Validate();

            if($Validation->fails()){
            //return $userinfo[0]['u_id'];

                $msg = 'Some field missing';

                return view('user.addUser')->withMsg($msg)->withUserinfo($userinfo2);
            }else{

               $status = DB::insert("INSERT INTO `user`(`u_password`, `u_email` , `u_type`,`last_name`) VALUES (?,?,?,?)" , [$req->password , $req->email , $req->type , $req->name ] );

               $msg = 'Registration Successful';
            //return $status;
        //return $req->password;
        //return $userinfo[0]['u_id'];
               return view('user.addUser')->withMsg($msg)->withUserinfo($userinfo2);

           }





        //return view('dashboard/dashboard' , $userinfo);
       }else{
        return redirect()->route('authenticationController.logout');
    }

}

public function add_factory(Request $req){



   if($req->session()->has('userinfo')){
    $userinfo1 = session('userinfo');
    $userinfo2 = json_decode(json_encode($userinfo1), true);

    $userinfo['userinfo'] = $userinfo2;


        //return $userinfo[0]['u_id'];
    return view('product.addFactory')->withMsg('')->withUserinfo($userinfo2)->with('page_name' , 'add_factory');
        //return view('dashboard/dashboard' , $userinfo);
}else{
    return redirect()->route('authenticationController.logout');
}
}







public function add_factoryPost(Request $req){



    if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;

        $userinfo['userinfo'] = $userinfo2;

        
        $Validation = Validator::make($req->all() , [
            'name'=>'required',
            'location'=>'required'
        ]);

        if($Validation->fails()){
            //return $userinfo[0]['u_id'];

            $msg = 'Some field missing';
            //return 'failed';
            return view('product.addFactory')->withMsg($msg)->withUserinfo($userinfo2);
        }
        else{

             $status = DB::insert("INSERT INTO `factory`(`name`, `location`) VALUES (?,?)" , [$req->name , $req->location ] );

               $msg = 'Registration Successful';

            return view('product.addFactory')->withMsg($msg)->withUserinfo($userinfo2);


        }

        
    }else{
        return redirect()->route('authenticationController.logout');
    }
}



public function ship_req_india(Request $req){



   if($req->session()->has('userinfo')){
    $userinfo1 = session('userinfo');
    $userinfo2 = json_decode(json_encode($userinfo1), true);

    $userinfo['userinfo'] = $userinfo2;


    $ship_reqs = DB::select('select s.* , u.last_name from shipment s , user u   where u.u_id = s.admin_id_req  and  s.status = 0  order by id desc');

    // return $ship_reqs;
    //dd($ship_reqs);


        //return $userinfo[0]['u_id'];
    return view('product.ship_req_india')->withMsg('none')->withUserinfo($userinfo2)->with('ship_reqs' , $ship_reqs )->with('page_name' , 'ship_req_india');
        //return view('dashboard/dashboard' , $userinfo);
}else{
    return redirect()->route('authenticationController.logout');
}
}


public function ship_req_bd(Request $req){

    $products = DB::select('select * from products');

        $users = DB::select("select * from user where u_type = 'user' ");
        //return $results;

        if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);



        $productList = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$userinfo1[0]->u_id]);

        $userinfo['userinfo'] = $userinfo2;
        //return $userinfo['userinfo'];
        //return $userinfo1[0]->u_id;
        //return $userinfo[0]['u_id'];
        return view('product.ship_req_bd')->withProducts($products)->withUsers($users)->withUserinfo($userinfo2)->with('productList' , $productList)->with('page_name' , 'ship_req_bd');
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

   
}


public function ship_req_bd_post(Request $req){

    //echo 'hellow';
    //echo $req->myinfo;

    $t = json_decode($req->myinfo);
    //echo $t->uid;


    $status = DB::statement('call shipment_cart(?,?,?)',[$t->uid , $t->pid , $t->qntity]);



    $products = DB::select('SELECT s.* , p.product_name FROM `shipment_temp` s , products p WHERE p.product_id = s.product_id and s.admin_id = (?)' , [$t->uid]);






    return json_encode($products);





   
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

    if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;

        $rawMaterials = DB::select('select * from raw_materials');

        $factories = DB::select('select * from factory');


        //return $userinfo[0]['u_id'];
        return view('product.add_raw_materials')->withMsg('')->withUserinfo($userinfo2)->with('rawMaterials' , $rawMaterials)->with('factories' , $factories)->with('page_name' , 'add_raw_materials');
        //return view('dashboard/dashboard' , $userinfo);
    }else{
        return redirect()->route('authenticationController.logout');
    }
}

public function add_raw_materialsPost(Request $req){


    if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;


        $factory_id = $req->factory_name;
        $materials_id = $req->materials;
        $qntity = $req->quantity;


        $status = DB::insert('INSERT INTO `factory_materials`(`factory_id`, `materials_id`, `qntity`) VALUES (? ,? , ?)' , [$factory_id , $materials_id , $qntity]);





        $rawMaterials = DB::select('select * from raw_materials');

        $factories = DB::select('select * from factory');


        //return $userinfo[0]['u_id'];
        return view('product.add_raw_materials')->withMsg('Successful')->withUserinfo($userinfo2)->with('rawMaterials' , $rawMaterials)->with('factories' , $factories);
        //return view('dashboard/dashboard' , $userinfo);
    }else{
        return redirect()->route('authenticationController.logout');
    }




}





}
