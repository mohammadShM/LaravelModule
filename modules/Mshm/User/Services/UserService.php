<?php

namespace Mshm\User\Services;

use Mshm\User\Models\User;

class UserService
{

    public static function changePassword($user, $newPassword)
    {
        /** @var User $user */
        $user->password = bcrypt($newPassword);
        $user->save();
    }

}
