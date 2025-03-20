<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    public function getCourseStatistics()
    {
        return [
            'total_courses' => Course::count(),
            'courses_by_status' => $this->getCoursesByStatus(),
            'courses_by_difficulty' => $this->getCoursesByDifficulty(),
            'courses_by_category' => $this->getCoursesByCategory(),
            'most_enrolled_courses' => $this->getMostEnrolledCourses(),
            'revenue_statistics' => $this->getCourseRevenueStatistics()
        ];
    }

    private function getCoursesByStatus()
    {
        return Course::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
    }

    private function getCoursesByDifficulty()
    {
        return Course::select('difficulty_level', DB::raw('COUNT(*) as count'))
            ->groupBy('difficulty_level')
            ->get()
            ->pluck('count', 'difficulty_level');
    }

    private function getCoursesByCategory()
    {
        return Course::select('category_id', DB::raw('COUNT(*) as count'))
            ->with('category:id,name')
            ->groupBy('category_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->category->name => $item->count
                ];
            });
    }

    private function getMostEnrolledCourses()
    {
        return Course::withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'title' => $course->title,
                    'enrollments' => $course->enrollments_count
                ];
            });
    }

    private function getCourseRevenueStatistics()
    {
        return Course::select(
            DB::raw('SUM(price) as total_revenue'),
            DB::raw('AVG(price) as average_price')
        )->first();
    }

    public function getCategoryStatistics()
    {
        return [
            'total_categories' => Category::count(),
            'categories_with_courses' => $this->getCategoriesWithCourseCount(),
            'top_categories' => $this->getTopCategoriesByCourseCount()
        ];
    }

    private function getCategoriesWithCourseCount()
    {
        return Category::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->get()
            ->mapWithKeys(function ($category) {
                return [
                    $category->name => $category->courses_count
                ];
            });
    }

    private function getTopCategoriesByCourseCount()
    {
        return Category::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'course_count' => $category->courses_count
                ];
            });
    }
    public function getTagStatistics()
    {
        return [
            'total_tags' => Tag::count(),
            'tags_with_courses' => $this->getTagsWithCourseCount(),
            'top_tags' => $this->getTopTagsByCourseCount()
        ];
    }

    private function getTagsWithCourseCount()
    {
        return Tag::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->get()
            ->mapWithKeys(function ($tag) {
                return [
                    $tag->name => $tag->courses_count
                ];
            });
    }

    private function getTopTagsByCourseCount()
    {
        return Tag::withCount('courses')
            ->orderBy('courses_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($tag) {
                return [
                    'name' => $tag->name,
                    'course_count' => $tag->courses_count
                ];
            });
    }
}