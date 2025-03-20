<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;
    public function view(User $currentUser, $userId)
    {
        return $currentUser->id === $userId || $currentUser->hasRole('admin');
    }
    public function update(User $currentUser, $userId)
    {
        return $currentUser->id === $userId || $currentUser->hasRole('admin');
    }

    public function delete(User $currentUser, $userId)
    {
        return $currentUser->hasRole('admin');
    }
}