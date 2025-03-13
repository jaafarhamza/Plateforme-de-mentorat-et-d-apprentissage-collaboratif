<?php

namespace App\Repositories\Interfaces;

interface TagRepositoryInterface extends RepositoryInterface
{
    public function findByName($name);
}