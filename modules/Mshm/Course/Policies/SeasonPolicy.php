<?php

namespace Mshm\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;

class SeasonPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    public function edit($user, $season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
            $season->course->teacher_id == $user->id;
    }

    public function delete($user,$season)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES)
                   && $season->course->teacher_id == $user->id) return true;
        return null;
    }

    public function change_confirmation_status($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

}
