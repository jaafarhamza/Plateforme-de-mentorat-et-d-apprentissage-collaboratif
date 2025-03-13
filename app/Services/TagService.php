<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interfaces\TagRepositoryInterface;

class TagService
{
    protected $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
    
    public function getAllTags()
    {
        return $this->tagRepository->all();
    }

    public function getTagById($id)
    {
        return $this->tagRepository->find($id);
    }

    public function findOrCreateTag($name)
    {
        $tag = $this->tagRepository->findByName($name);
        
        if (!$tag) {
            $tag = $this->createTag(['name' => $name]);
        }
        
        return $tag;
    }

    public function createTag(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:50|unique:tags,name',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->tagRepository->create($data);
    }

    public function updateTag($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:50|unique:tags,name,' . $id,
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag($id)
    {
        return $this->tagRepository->delete($id);
    }
}