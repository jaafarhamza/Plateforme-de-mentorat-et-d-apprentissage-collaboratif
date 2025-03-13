<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CourseService;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function index()
    {
        try {
            $courses = $this->courseService->getAllCourses();
            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error getting courses: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve courses'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function byCategory($categoryId)
    {
        try {
            $courses = $this->courseService->getCoursesByCategory($categoryId);
            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error getting courses by category: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve courses'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function byMentor($mentorId)
    {
        try {
            $courses = $this->courseService->getCoursesByMentor($mentorId);
            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error getting courses by mentor: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve courses'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $course = $this->courseService->getCourseById($id);
            return response()->json([
                'status' => 'success',
                'data' => $course
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error getting course: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        try {
            $course = $this->courseService->createCourse($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Course created successfully',
                'data' => $course
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            \Log::error("Error creating course: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $course = $this->courseService->updateCourse($id, $request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Course updated successfully',
                'data' => $course
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error updating course: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->courseService->deleteCourse($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Course deleted successfully'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::error("Error deleting course: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}