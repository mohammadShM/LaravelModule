<?php /** @noinspection PhpMissingReturnTypeInspection */

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

    public function download($user, $lesson)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->id === $lesson->course->teacher_id ||
            $lesson->course->hasStudent($user->id) ||
            $lesson->is_free
        ) {
            return true;
        }
        return false;
    }

}
