<?php

namespace Mshm\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Mshm\User\Repositories\UserRepo;

class ValidTeacher implements Rule
{

    public function passes($attribute, $value)
    {
        $user = resolve(UserRepo::class)->findById($value);
        return $user->hasPermissionTo('teach');
    }

    public function message()
    {
        return " کاربر انتخاب شده یک مدرس معتبر نیست . ";
    }
}
