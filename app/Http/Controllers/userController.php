<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;

class userController extends Controller
{

    public function addUser(Request $req){

    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;





        //return $req;
        //return $userinfo[0]['u_id'];
            return view('user.addUser')->withMsg('')->withUserinfo($userinfo2);
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
    return view('product.addFactory')->withMsg('')->withUserinfo($userinfo2);
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


        //return $userinfo[0]['u_id'];
    return view('product.ship_req_india')->withMsg('none')->withUserinfo($userinfo2);
        //return view('dashboard/dashboard' , $userinfo);
}else{
    return redirect()->route('authenticationController.logout');
}
}


public function add_raw_materials(Request $req){



    if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;


        //return $userinfo[0]['u_id'];
        return view('product.add_raw_materials')->withMsg('none')->withUserinfo($userinfo2);
        //return view('dashboard/dashboard' , $userinfo);
    }else{
        return redirect()->route('authenticationController.logout');
    }
}

}
