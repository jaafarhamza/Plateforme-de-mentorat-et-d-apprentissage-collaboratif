<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\Interfaces\TagRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TagRepository implements TagRepositoryInterface
{
    protected $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->orderBy('name')->get();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $tag = $this->find($id);
        $tag->update($data);
        return $tag;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}