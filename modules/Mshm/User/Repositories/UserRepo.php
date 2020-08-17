<?php

namespace Mshm\User\Repositories;

use Mshm\User\Models\User;

class UserRepo
{

    public function findByEmail($email)
    {
        return User::query()->where('email', $email)->first();
    }

	public function getTeachers()
	{
        return User::permission('teach')->get() ;
	}

    public function findById($id)
    {
        return User::find($id);
	}

}
