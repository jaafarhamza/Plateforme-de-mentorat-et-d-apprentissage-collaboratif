<?php

namespace App\Repositories\Interfaces;

interface CourseRepositoryInterface extends RepositoryInterface
{
    public function getByCategory($categoryId);
    public function getByMentor($mentorId);
}