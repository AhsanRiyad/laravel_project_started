<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class accountController extends Controller
{
    //

    public function money_transfer( Request $req){

    	//echo $amount.' '.$user_id;


        $balance = DB::select('select balance_available from account where user_id = 0');



        //return $req;
        ///return $userinfo[0]['u_id'];
        return view('accounts.money_transfer')->withMsg('')->withUserinfo($req->userinfo)->with('balance' , $balance)->with('page_name' , 'money_transfer');
        //return view('dashboard/dashboard' , $userinfo);
        

    }




    public function money_transferPost( Request $req){

    	//echo $amount.' '.$user_id;
    	

        


        $status = DB::statement('call money_transfer(? , ?)' , [$req->user_id , $req->amount]);


        $balance = DB::select('select balance_available from account where user_id = 0');


        //return $req;
        ///return $userinfo[0]['u_id'];
        return view('accounts.money_transfer')->withMsg('Successful')->withUserinfo($req->userinfo)->with('balance' , $balance)->with('page_name' , 'money_transfer');
        //return view('dashboard/dashboard' , $userinfo);
        

    }



    public function money_transfer_status( Request $req){

    	//echo $amount.' '.$user_id;
    	

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $money_transfer = DB::select('SELECT m.* , u.last_name as transfered_by , n.last_name as received_by FROM money_transfer m LEFT OUTER JOIN user u ON m.transfered_by=u.u_id LEFT OUTER JOIN user n ON m.received_by=n.u_id order by id desc');


        //return $req;
        ///return $userinfo[0]['u_id'];
        return view('accounts.money_transfer_status')->withMsg('')->withUserinfo($req->userinfo)->with('money_transfer' , $money_transfer)->with('page_name' , 'money_transfer_status');
        //return view('dashboard/dashboard' , $userinfo);
        
    }




    public function shipment_status( Request $req){

    	//echo $amount.' '.$user_id;
    	

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $shipment_log = DB::select('select * from shipment order by id desc');


        //return $req;
        ///return $userinfo[0]['u_id'];
        return view('accounts.shipment_status')->withMsg('')->withUserinfo($req->userinfo)->with('shipment_log' , $shipment_log)->with('page_name' , 'shipment_status');
        //return view('dashboard/dashboard' , $userinfo);
        

    }

    public function sales_report( Request $req){

        //echo $amount.' '.$user_id;
        

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $reports = DB::select('SELECT o.* , (o.total_amount - o.paid) as due, u.last_name FROM `order_t` o, user u WHERE order_date = CURDATE() and u.u_id = o.user_id order by o.order_id desc
            ');


        return view('accounts.reports')->withUserinfo($req->userinfo)->with('reports' , $reports)->with('page_name' , 'reports');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        

    }



    public function all_sales( Request $req){

        //echo $amount.' '.$user_id;
        

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $reports = DB::select('SELECT o.* , (o.total_amount - o.paid) as due, u.last_name FROM `order_t` o, user u WHERE  u.u_id = o.user_id order by order_id desc
            ');


        return view('accounts.all_sales')->withUserinfo($req->userinfo)->with('reports' , $reports)->with('page_name' , 'reports');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        

    }


    public function sales_report_yearly( Request $req){

        //echo $amount.' '.$user_id;
       

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $reports = DB::select('SELECT o.* , (o.total_amount - o.paid) as due, u.last_name FROM `order_t` o, user u WHERE order_date = CURDATE() and u.u_id = o.user_id
            ');


        return view('accounts.reports')->withUserinfo($req->userinfo)->with('reports' , $reports);
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        

    }



    public function money_transfer_request( Request $req){

        //echo $amount.' '.$user_id;
        

            //$balance = DB::select('select balance_available from account where user_id = 0');

        $money = DB::select('SELECT * FROM `money_transfer` WHERE STATUS = 0
            ');


        return view('accounts.money_transfer_req')->withUserinfo($req->userinfo)->with('money' , $money)->withMsg('')->with('page_name' , 'money_transfer_request');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        

    }





    public function money_transfer_requestPost( Request $req){

        

            //$balance = DB::select('select balance_available from account where user_id = 0');



        $status= DB::update('UPDATE `money_transfer` SET `receive_date`= sysdate(),`received_by`= (?),`status`=1 WHERE id = (?)' , [$req->admin_id , $req->id]);

        $money = DB::select('SELECT * FROM `money_transfer` WHERE STATUS = 0
            ');

        return view('accounts.money_transfer_req')->withUserinfo($req->userinfo)->with('money' , $money)->withMsg('Approved')->with('page_name' , 'money_transfer_request');
        //return $req;
        ///return $userinfo[0]['u_id'];
            //return view('accounts.shipment_status')->withMsg('')->withUserinfo($userinfo2)->with('shipment_log' , $shipment_log);
        //return view('dashboard/dashboard' , $userinfo);
        

    }




    public function money_accept( Request $req , $id , $admin_id){

    }





}
