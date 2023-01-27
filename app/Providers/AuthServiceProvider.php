<?php

namespace App\Providers;

use App\managers\UserManager;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        foreach (config("permissions") as $permission) {
            $module = $permission['module'];
            $key = $permission['key'];
            $permissionName = strtolower($module.$key);
            Gate::define($permissionName, function ($user) use ($permissionName) {
                if ($user->alphaRole == UserManager::AlphaRoleSuper)
                    return true;
                $userPermissions = session('permissions');
                if (empty($userPermissions))
                    return false;
                if (in_array($permissionName, $userPermissions))
                    return true;
            });
        }
        Passport::routes();
    }
}
