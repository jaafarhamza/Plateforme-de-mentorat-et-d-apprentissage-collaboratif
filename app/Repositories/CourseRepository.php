<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    protected $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->with(['category', 'mentor'])->get();
    }

    public function find($id)
    {
        return $this->model->with(['category', 'mentor', 'tags'])->findOrFail($id);
    }

    public function getByCategory($categoryId)
    {
        return $this->model->where('category_id', $categoryId)
                  ->with(['mentor'])
                  ->get();
    }

    public function getByMentor($mentorId)
    {
        return $this->model->where('mentor_id', $mentorId)
                  ->with(['category'])
                  ->get();
    }

    public function create(array $data)
    {
        $course = $this->model->create($data);
        
        if (isset($data['tags']) && is_array($data['tags'])) {
            $course->tags()->attach($data['tags']);
        }
        
        return $course;
    }

    public function update($id, array $data)
    {
        $course = $this->find($id);
        $course->update($data);
        
        if (isset($data['tags']) && is_array($data['tags'])) {
            $course->tags()->sync($data['tags']);
        }
        
        return $course;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}