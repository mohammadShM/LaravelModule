<?php

namespace Mshm\User\Repositories;

use Mshm\User\Models\User;

class UserRepo
{

    public function findByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }

}
