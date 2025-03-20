<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Course $course): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Course $course): bool
    {
        return false;
    }

    public function delete(User $user, Course $course): bool
    {
        return false;
    }

    public function restore(User $user, Course $course): bool
    {
        return false;
    }

    public function forceDelete(User $user, Course $course): bool
    {
        return false;
    }
}