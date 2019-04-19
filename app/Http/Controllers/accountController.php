<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class accountController extends Controller
{
    //

    public function money_transfer( Request $req){

    	//echo $amount.' '.$user_id;
    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            $balance = DB::select('select balance_available from account where user_id = 0');



        //return $req;
        ///return $userinfo[0]['u_id'];
            return view('accounts.money_transfer')->withMsg('')->withUserinfo($userinfo2)->with('balance' , $balance);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }




    public function money_transferPost( Request $req){

    	//echo $amount.' '.$user_id;
    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            


            $status = DB::statement('call money_transfer(? , ?)' , [$req->user_id , $req->amount]);


            $balance = DB::select('select balance_available from account where user_id = 0');


        //return $req;
        ///return $userinfo[0]['u_id'];
            return view('accounts.money_transfer')->withMsg('Successful')->withUserinfo($userinfo2)->with('balance' , $balance);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }



    public function money_transfer_status( Request $req){

    	//echo $amount.' '.$user_id;
    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');

            $money_transfer = DB::select('select * from money_transfer ');


        //return $req;
        ///return $userinfo[0]['u_id'];
            return view('accounts.money_transfer_status')->withMsg('')->withUserinfo($userinfo2)->with('money_transfer' , $money_transfer);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }




    public function shipment_status( Request $req){

    	//echo $amount.' '.$user_id;
    	if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');

            $shipment_log = DB::select('select * from shipment order by id desc');


        //return $req;
        ///return $userinfo[0]['u_id'];
            return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }









}
