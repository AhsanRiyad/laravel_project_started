<?php

namespace App\Http\Middleware;

use Closure;

class sessionCheck
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



        if($request->session()->has('userinfo')){


            $userinfo1 = session('userinfo');
            $userinfo2 = json_decode(json_encode($userinfo1), true);

            $userinfo['userinfo'] = $userinfo2;
            $request->userinfo = $userinfo2;

            return $next($request);
        //return $userinfo[0]['u_id'];

        //return view('dashboard/dashboard' , $userinfo);
            }else{
            return redirect()->route('authenticationController.logout');
            }





        
    }





}
