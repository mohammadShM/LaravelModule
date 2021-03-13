<?php
/** @noinspection TypeUnsafeComparisonInspection */

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

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    public function index($user): bool
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES);
    }

    public function createLesson($user, $course): bool
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
                $course->teacher_id == $user->id);
    }

    public function edit($user, $course): bool
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
            $course->teacher_id == $user->id;
    }

    public function delete($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

    public function change_confirmation_status($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        return null;
    }

    public function details($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES)
            && $course->teacher_id == $user->id) return true;
        return null;
    }

    public function createSeason($user, $course)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES)
            && $course->teacher_id == $user->id) return true;
        return null;
    }

}
