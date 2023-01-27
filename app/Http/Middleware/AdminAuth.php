<?php

namespace App\Http\Middleware;

use App\managers\UserManager;
use App\Models\Organization;
use App\TraitLibraries\RolePermissionEngine;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    use RolePermissionEngine;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)

    {

        $isAuthenticatedAdmin = (Auth::guard('admin')->check() && Auth::guard('admin')->user()->id  && Auth::guard('admin')->user()->active) ;
        //This will be excecuted if the new authentication fails.
        if (! $isAuthenticatedAdmin) {
            $check_active = isset(Auth::guard('admin')->user()->active) &&  Auth::guard('admin')->user()->active==0 ? false :true;
            Auth::guard('admin')->logout();
            session()->forget('permissions');
            session()->save();
            if(!$check_active) {
                session()->flash('error', 'Your account has been deactivated, please contact administrator');
            }

            return redirect(route('admin.login'));
        }
        $auth_user = Auth::guard('admin')->user();

        $role = $auth_user->alphaRole;

        if ($role != UserManager::AlphaRoleSuper) {
            $this->setMergeRolePermissions($auth_user->roles);

            if (!empty($this->permissions))
                $request->session()->put('permissions', $this->permissions);
        }
        //dd($this->permissions);

        $request->session()->put('user_role', $role);
        return $next($request);
        //return $next($request);
    }
}
