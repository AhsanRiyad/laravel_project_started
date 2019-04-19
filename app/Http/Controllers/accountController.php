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

    public function sales_report( Request $req){

        //echo $amount.' '.$user_id;
        if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');

            $reports = DB::select('SELECT o.* , (o.total_amount - o.paid) as due, u.last_name FROM `order_t` o, user u WHERE order_date = CURDATE() and u.u_id = o.user_id
');


            return view('accounts.reports')->withUserinfo($userinfo2)->with('reports' , $reports);
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }


 public function sales_report_yearly( Request $req){

        //echo $amount.' '.$user_id;
        if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');

            $reports = DB::select('SELECT o.* , (o.total_amount - o.paid) as due, u.last_name FROM `order_t` o, user u WHERE order_date = CURDATE() and u.u_id = o.user_id
');


            return view('accounts.reports')->withUserinfo($userinfo2)->with('reports' , $reports);
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }



     public function money_transfer_request( Request $req){

        //echo $amount.' '.$user_id;
        if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');

            $money = DB::select('SELECT * FROM `money_transfer` WHERE STATUS = 0
');


            return view('accounts.money_transfer_req')->withUserinfo($userinfo2)->with('money' , $money)->withMsg('');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }





     public function money_transfer_requestPost( Request $req){

        if($req->session()->has('userinfo')){
            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;

            //$balance = DB::select('select balance_available from account where user_id = 0');



            $status= DB::update('UPDATE `money_transfer` SET `receive_date`= sysdate(),`received_by`= (?),`status`=1 WHERE id = (?)' , [$req->admin_id , $req->id]);

              $money = DB::select('SELECT * FROM `money_transfer` WHERE STATUS = 0
');

            return view('accounts.money_transfer_req')->withUserinfo($userinfo2)->with('money' , $money)->withMsg('Approved');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        }else{
            return redirect()->route('authenticationController.logout');
        }

    }









     public function money_accept( Request $req , $id , $admin_id){

       





}





}
