<?php

namespace Mshm\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;

class CoursePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
    }

    public function edit($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
            $course->teacher_id == $user->id;
    }

}
