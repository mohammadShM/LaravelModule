<?php

namespace Mshm\Category\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;
use Mshm\User\Models\User;

class CategoryPolicy
{

    use HandlesAuthorization;

    public function manage(User $user)
    {
        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_CATEGORIES);
    }

}
