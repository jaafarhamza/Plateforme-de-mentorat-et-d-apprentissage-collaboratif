<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserProfileService
{
    public function getUserProfile($userId)
    {
        return User::with(['roles', 'permissions'])
            ->findOrFail($userId);
    }

    public function updateProfile($userId, array $data)
    {
        // Validate input
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId,
            'biography' => 'nullable|string|max:1000',
            'specialty' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|max:2048' 
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Find user
        $user = User::findOrFail($userId);

        if (isset($data['profile_picture'])) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $picturePath = $data['profile_picture']->store('profile_pictures', 'public');
            $data['profile_picture'] = $picturePath;
        }

        // Update user profile
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function deleteProfile($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->profile_picture) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Delete user
        $user->delete();

        return true;
    }
}