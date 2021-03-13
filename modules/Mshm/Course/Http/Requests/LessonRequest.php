<?php

namespace Mshm\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mshm\Course\Models\Course;
use Mshm\Course\Rules\ValidSeason;
use Mshm\Course\Rules\ValidTeacher;
use Mshm\Media\Services\MediaFileService;

class LessonRequest extends FormRequest
{

    public function authorize(): bool
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        return auth()->check() == true;
    }


    public function rules(): array
    {
        $rules = [
            "title" => "required|min:3|max:190",
            "slug" => "nullable|min:3|max:190",
            "number" => "nullable|numeric",
            "time" => "required|numeric|min:0|max:255",
            "season_id" => [new ValidSeason()],
            "is_free" => "required|boolean",
            "lesson_file" => "required|file|mimes:avi,mkv,mp4,zip,rar,m4v",
        ];
        // for update
        if (request()->method === 'PATCH') {
            $rules["lesson_file"] = "nullable|file|mimes:".MediaFileService::getExtensions();
        }
        // for update and store
        return $rules;
    }

    public function attributes(): array
    {
        return [
            "title" => "عنوان درس",
            "slug" => "عنوان انگلیسی درس",
            "number" => "شماره درس",
            "time" => "مدت زمان درس",
            "season_id" => "سرفصل",
            "free" => "رایگان",
            "lesson_file" => "فایل درس",
            "body" => "توضیحات درس",
        ];
    }

}
