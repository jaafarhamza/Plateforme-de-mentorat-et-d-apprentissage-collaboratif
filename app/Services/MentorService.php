<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class MentorService
{
    public function getMentorCourses($mentorId)
    {
        return Course::where('mentor_id', $mentorId)
            ->with(['category', 'tags'])
            ->withCount('enrollments')
            ->get();
    }

    public function getMentorStudents($mentorId)
    {
        $mentorCourseIds = Course::where('mentor_id', $mentorId)
            ->pluck('id');

        return User::whereHas('enrolledCourses', function ($query) use ($mentorCourseIds) {
            $query->whereIn('courses.id', $mentorCourseIds);
        })
        ->with(['roles' => function ($query) {
            $query->where('name', 'student');
        }])
        ->select('id', 'name', 'email')
        ->get();
    }

    public function getMentorPerformance($mentorId)
    {
        $courses = Course::where('mentor_id', $mentorId)->get();

        // Total courses
        $totalCourses = $courses->count();

        // Total enrolled students
        $totalEnrollments = DB::table('course_enrollments')
            ->whereIn('course_id', $courses->pluck('id'))
            ->count();

        $completedEnrollments = DB::table('course_enrollments')
            ->whereIn('course_id', $courses->pluck('id'))
            ->where('status', 'completed')
            ->count();

        $totalRevenue = $courses->sum('price');

        $averageRating = DB::table('course_ratings')
            ->whereIn('course_id', $courses->pluck('id'))
            ->avg('rating');

        return [
            'total_courses' => $totalCourses,
            'total_students_enrolled' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'completion_rate' => $totalCourses > 0 
                ? round(($completedEnrollments / $totalEnrollments) * 100, 2) 
                : 0,
            'total_revenue' => $totalRevenue,
            'average_course_rating' => round($averageRating, 2) ?? 0,
            'courses' => $courses->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'enrollments' => $course->enrollments_count,
                    'price' => $course->price
                ];
            })
        ];
    }
}