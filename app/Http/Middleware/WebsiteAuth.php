<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebsiteAuth
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

        $user_detail = Auth::guard('web')->user();
        $isAuthenticatedAdmin = (Auth::guard('web')->check() && !empty($user_detail->id));
        //dd($isAuthenticatedAdmin);

        //This will be excecuted if the new authentication fails.
        if (! $isAuthenticatedAdmin) {
            $request->session()->flash('error','Please first login to perform this action');
            return redirect(route('login_page'),301);

        }else{
           if($user_detail->active!= 1){

               $request->session()->flash('error','Your account is not active');
               return redirect(route('logout'),301);
           }
        }
        return $next($request);
        //return $next($request);
    }
}
