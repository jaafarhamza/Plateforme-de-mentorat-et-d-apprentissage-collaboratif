<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MentorService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MentorController extends Controller
{
    protected $mentorService;

    public function __construct(MentorService $mentorService)
    {
        $this->mentorService = $mentorService;
    }

    public function listCourses($mentorId)
    {
        try {
            $mentor = User::findOrFail($mentorId);

            // Additional explicit role check
            if (!$mentor->hasRole('mentor')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a mentor',
                    'debug' => [
                        'user_id' => $mentorId,
                        'user_roles' => $mentor->roles->pluck('name')
                    ]
                ], Response::HTTP_BAD_REQUEST);
            }

            // Get mentor's courses
            $courses = $this->mentorService->getMentorCourses($mentorId);

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ], Response::HTTP_OK);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
                'debug' => [
                    'user_id' => $mentorId
                ]
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'debug' => [
                    'user_id' => $mentorId
                ]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function listStudents($mentorId)
    {
        try {
            // Verify the mentor exists
            $mentor = User::findOrFail($mentorId);

            // Ensure the user is actually a mentor
            if (!$mentor->hasRole('mentor')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a mentor'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Get students enrolled in mentor's courses
            $students = $this->mentorService->getMentorStudents($mentorId);

            return response()->json([
                'status' => 'success',
                'data' => $students
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getPerformance($mentorId)
    {
        try {
            $mentor = User::findOrFail($mentorId);

            if (!$mentor->hasRole('mentor')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Specified user is not a mentor'
                ], Response::HTTP_BAD_REQUEST);
            }

            $performance = $this->mentorService->getMentorPerformance($mentorId);

            return response()->json([
                'status' => 'success',
                'data' => $performance
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
}