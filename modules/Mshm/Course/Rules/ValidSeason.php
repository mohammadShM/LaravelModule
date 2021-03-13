<?php

namespace Mshm\Course\Rules;

use Illuminate\Contracts\Validation\Rule;
use Mshm\Course\Models\Season;
use Mshm\Course\Repositories\SeasonRepo;
use Mshm\User\Repositories\UserRepo;

class ValidSeason implements Rule
{

    public function passes($attribute, $value): bool
    {
        $season = resolve(SeasonRepo::class)->findByIdAndCourseId($value, request()->route('course'));
        if ($season) {
            return true;
        }
        return false;
    }

    public function message(): string
    {
        return "سرفصل انتخاب شده معتبر نمی باشد . ";
    }
}
