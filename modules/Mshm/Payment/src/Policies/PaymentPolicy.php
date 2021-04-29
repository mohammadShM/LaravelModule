<?php /** @noinspection PhpMissingReturnTypeInspection */

namespace Mshm\Payment\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Mshm\RolePermissions\Models\Permission;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_PAYMENTS)){
            return true ;
        }
    }

}
