<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\UserProfileService;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserProfileController extends Controller
{
    use AuthorizesRequests;
    
    protected $userProfileService;

    public function __construct(UserProfileService $userProfileService)
    {
        $this->userProfileService = $userProfileService;
    }

    public function show($id)
    {
        try {
            $user = $this->userProfileService->getUserProfile($id);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('update', [User::class, $id]);

            $user = $this->userProfileService->updateProfile($id, $request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Profile updated successfully',
                'data' => $user
            ], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Authorize the action
            $this->authorize('delete', $user);

            $this->userProfileService->deleteProfile($id);

            return response()->json([
                'status' => 'success',
                'message' => 'User profile deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}