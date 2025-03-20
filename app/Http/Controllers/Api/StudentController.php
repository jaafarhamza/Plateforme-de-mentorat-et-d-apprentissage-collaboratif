<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }
    public function listCourses($studentId)
    {
        try {
            $student = User::findOrFail($studentId);

            if (!$student->hasRole('student')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a student'
                ], Response::HTTP_BAD_REQUEST);
            }

            $courses = $this->studentService->getEnrolledCourses($studentId);

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
    public function getProgress($studentId)
    {
        try {
            $student = User::findOrFail($studentId);

            if (!$student->hasRole('student')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a student'
                ], Response::HTTP_BAD_REQUEST);
            }

            $progress = $this->studentService->getStudentProgress($studentId);

            return response()->json([
                'status' => 'success',
                'data' => $progress
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getBadges($studentId)
    {
        try {
            $student = User::findOrFail($studentId);

            if (!$student->hasRole('student')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a student'
                ], Response::HTTP_BAD_REQUEST);
            }

            $badges = $this->studentService->getStudentBadges($studentId);

            return response()->json([
                'status' => 'success',
                'data' => $badges
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}