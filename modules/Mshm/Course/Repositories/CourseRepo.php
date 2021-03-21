<?php

namespace Mshm\Course\Repositories;

use Illuminate\Support\Str;
use Mshm\Course\Models\Course;
use Mshm\Course\Models\Lesson;

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
            "confirmation_status" => Course::CONFIRMATION_STATUS_PENDING,
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

    public function update($id, $values)
    {
        return Course::where('id', $id)->update([
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

    public function updateConfirmationStatus($id, string $status)
    {
        return Course::where('id', $id)->update([
            'confirmation_status' => $status
        ]);
    }

    public function updateStatus($id, string $status)
    {
        return Course::where('id', $id)->update([
            'status' => $status
        ]);
    }

    public function getCoursesByTeacherId(?int $id)
    {
        return Course::where('teacher_id', $id)->get();
    }

    public function latestCourses()
    {
        return Course::where('confirmation_status', Course::CONFIRMATION_STATUS_ACCEPTED)
            ->latest()->take(8)->get();
    }

    public function getDuration($id)
    {
        return Lesson::where('course_id', $id)
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)
            ->sum('time');
    }

    public function getLessonsCount($id): int
    {
        return Lesson::where('course_id', $id)
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED)
            ->count();
    }

    public function addStudentToCourse(Course $course, $student_id)
    {
        if (!$this->getCourseStudentById($course, $student_id)) {
            $course->students()->attach($student_id);
        }
    }

    public function getCourseStudentById(Course $course, $studentId)
    {
        return $course->students()->where("id", $studentId)->first();
    }

    public function hasStudent(Course $course, $student_id)
    {
        return $course->students->contains($student_id);
    }

}
