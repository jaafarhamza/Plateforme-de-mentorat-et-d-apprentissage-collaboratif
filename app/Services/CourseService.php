<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }
    
    public function getAllCourses()
    {
        return $this->courseRepository->all();
    }

    public function getCoursesByCategory($categoryId)
    {
        return $this->courseRepository->getByCategory($categoryId);
    }

    public function getCoursesByMentor($mentorId)
    {
        return $this->courseRepository->getByMentor($mentorId);
    }

    public function getCourseById($id)
    {
        return $this->courseRepository->find($id);
    }

    public function createCourse(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'required|integer|min:1',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:open,in_progress,completed',
            'category_id' => 'required|exists:categories,id',
            'mentor_id' => 'required|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->courseRepository->create($data);
    }

    public function updateCourse($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'sometimes|integer|min:1',
            'difficulty_level' => 'sometimes|in:beginner,intermediate,advanced',
            'status' => 'sometimes|in:open,in_progress,completed',
            'category_id' => 'sometimes|exists:categories,id',
            'mentor_id' => 'sometimes|exists:users,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->courseRepository->update($id, $data);
    }

    public function deleteCourse($id)
    {
        return $this->courseRepository->delete($id);
    }
}