<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\dashboardModel;
use DB;

class dashboardController extends Controller
{
    public static function dashboard(Request $req){

    	

        $revenue = DB::select('select sum(total_amount) as c from order_t
');
        $visit = DB::select('select total as c from visitcounter');

        
        $order = DB::select('SELECT COUNT(*) as c from order_t
        ');

        $products = DB::select('SELECT COUNT(*) as c from products
        ');



        //return $revenue;


    	//return $userinfo[0]['u_id'];
    	return view('dashboard/dashboard')->with('page_name' , 'dashboard')->withVisit($visit[0]->c)->withOrder($order[0]->c)->withProducts($products[0]->c)->withRevenue($revenue[0]->c)->withUserinfo($req->userinfo);
    	

    	
    }
}
