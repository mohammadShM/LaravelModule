<?php

namespace Mshm\Course\Repositories;

use Illuminate\Support\Str;
use Mshm\Course\Models\Course;

class CourseRepo
{

    public function store($values)
    {
        return Course::create([
            "teacher_id" => $values->teacher_id,
            "category_id" => $values->category_id,
            "banner_id" => $values->banner_id,
            "title" => $values->title,
            "slug" => Str::slug($values->title),
            "priority" => $values->priority,
            "price" => $values->price,
            "percent" => $values->percent,
            "type" => $values->type,
            "status" => $values->status,
            "body" => $values->body,
        ]);
    }

    public function paginate()
    {
        return Course::paginate();
    }

	public function findById($id)
	{
        return Course::findOrFail($id);
	}

}
