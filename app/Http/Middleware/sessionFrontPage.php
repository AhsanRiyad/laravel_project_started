<?php

namespace App\Http\Middleware;
use Closure;
use App\productModel;
use DB;
use App\multipleSelectModel;


class sessionFrontPage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {


        //error_log('inside middleware');
        if($request->session()->has('userinfo')){
            $loginStatus = true;

            $userinfo = session('userinfo');
    //print_r($userinfo);
            $userinfo2 = json_decode(json_encode($userinfo), true);
    //print_r($userinfo2);

    //echo $userinfo2[0]['u_id'];
            $uid =  $userinfo2[0]['u_id'];
            $request->userinfo = $userinfo2;
    //for references
    //https://www.geeksforgeeks.org/what-is-stdclass-in-php/
            $c = productModel::cart_count($uid);
            $cart_count = $c[0]->cart_count;
            //error_log('inside the middleware');
            //return;
    //print_r($c[0]);
    //echo $c[0]->cart_count;



            $request->s_uid = $uid;
            $request->s_cart_count = $cart_count;
            $request->s_login_status = $loginStatus;


    //return redirect()->route('a_pos.index');

        }else{
            $cart_count = 0;
            $loginStatus = false;
            //error_log('inside the middleware');
            //return;
            $request->s_uid = null;
            $request->s_cart_count = $cart_count;
            $request->s_login_status = $loginStatus;

    //return redirect()->route('authentication.login');

        }
        //return 'hellow';
        return $next($request);

         //return redirect()->route('authenticationController.logout');
        
    }
}
