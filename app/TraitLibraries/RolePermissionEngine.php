<?php
/**
 * Created by PhpStorm.
 * User: warispopal
 * Date: 2/7/19
 * Time: 12:17 AM
 */

namespace App\TraitLibraries;

trait RolePermissionEngine
{
    protected $permissions = Array();

    protected function setMergeRolePermissions($roles)
    {
        foreach ($roles as $role) {
            $permission = json_decode($role->permissions, 1);
            if (!empty($permission)) {
                $this->permissions = array_merge($this->permissions, $permission);
            }
        }
    }

}
