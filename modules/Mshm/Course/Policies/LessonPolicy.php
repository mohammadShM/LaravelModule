<?php
/** @noinspection PhpInconsistentReturnPointsInspection */

namespace Mshm\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;

class LessonPolicy
{

    use HandlesAuthorization;

    public function edit($user, $lesson): bool
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
                $user->id == $lesson->course->teacher_id);
    }

    public function delete($user, $lesson): bool
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
                $user->id == $lesson->course->teacher_id);
    }

}
