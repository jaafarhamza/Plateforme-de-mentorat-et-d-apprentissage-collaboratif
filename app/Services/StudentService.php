<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class StudentService
{

    public function getEnrolledCourses($studentId)
    {
        return Course::whereHas('enrollments', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
        ->with(['mentor', 'category', 'tags'])
        ->get();
    }

    public function getStudentProgress($studentId)
    {
        $courses = Course::whereHas('enrollments', function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
        ->with(['videos', 'enrollments' => function ($query) use ($studentId) {
            $query->where('user_id', $studentId);
        }])
        ->get()
        ->map(function ($course) {
            $totalVideos = $course->videos->count();
            $watchedVideos = $course->enrollments->first()->watched_videos_count ?? 0;
            
            return [
                'course_id' => $course->id,
                'course_title' => $course->title,
                'total_videos' => $totalVideos,
                'watched_videos' => $watchedVideos,
                'progress_percentage' => $totalVideos > 0 
                    ? round(($watchedVideos / $totalVideos) * 100, 2) 
                    : 0,
                'status' => $this->determineProgressStatus($watchedVideos, $totalVideos)
            ];
        });

        return $courses;
    }

    private function determineProgressStatus($watchedVideos, $totalVideos)
    {
        if ($totalVideos === 0) return 'not_started';
        if ($watchedVideos === 0) return 'not_started';
        if ($watchedVideos < $totalVideos) return 'in_progress';
        return 'completed';
    }

    public function getStudentBadges($studentId)
    {
        return [
            'total_badges' => 0,
            'badges' => []
        ];
    }
}