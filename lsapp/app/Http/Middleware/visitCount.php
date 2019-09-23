<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class visitCount
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


        DB::insert('UPDATE `visitcounter` SET `total`=total+1 WHERE id= 0');

        return $next($request);
    }
}
