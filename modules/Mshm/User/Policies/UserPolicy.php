<?php

namespace Mshm\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;

class UserPolicy
{

    use HandlesAuthorization;

    public function index($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS)) {
            return true;
        }
        return null;
    }

    public function edit($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS)) {
            return true;
        }
        return null;
    }

    public function manualVerify($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS)) {
            return true;
        }
        return null;
    }

    public function addRole($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS)) {
            return true;
        }
        return null;
    }

    public function removeRole($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_USERS)) {
            return true;
        }
        return null;
    }

    public function editProfile($user)
    {
        if (auth()->check()) {
            return true;
        }
        return null;
    }

}
