<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class userController extends Controller
{
    
    public function addUser(Request $req){



    	if($req->session()->has('userinfo')){
        $userinfo1 = session('userinfo');
        $userinfo2 = json_decode(json_encode($userinfo1), true);

        $userinfo['userinfo'] = $userinfo2;


        //return $userinfo[0]['u_id'];
        return view('user.addUser')->withMsg('none')->withUserinfo($userinfo2);
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
        return view('product.addFactory')->withMsg('none')->withUserinfo($userinfo2);
        //return view('dashboard/dashboard' , $userinfo);
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
