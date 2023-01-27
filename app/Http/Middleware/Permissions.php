<?php

namespace App\Http\Middleware;

use App\managers\UserManager;
use Closure;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        try {
            if(session()->get('show_menu_'.$request->route()->uri) == "false"){
                throw new \Exception("You don't have access to the page.");
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        if (in_array($request->user('admin')->alphaRole, [UserManager::AlphaRoleSuper]))
            return $next($request);

        /* @permission for Controllers */
        $route = $request->route();
        $controller = $route->getController();
        $modulePermissions = @$controller->permissions;
        if (!empty($modulePermissions)) {
            try {
                if (!is_array($modulePermissions))
                    throw new \Exception("{$route->getName()} Controller \$assignedPermission must be an array.");

                $action = $route->getActionMethod();

                if (!empty($action)) {
                    if(array_key_exists($action, $modulePermissions)){
                        $permissions = $request->session()->get('permissions');
                        $key = $modulePermissions[$action];
                        if (!in_array(strtolower($controller->module.$key), $permissions))
                            throw new \Exception("You don't have access to the page.");
                    }
                }
            } catch (\Exception $e) {
                return redirect()->back()->withErrors([$e->getMessage()]);
            }
        }
        return $next($request);
    }
}
