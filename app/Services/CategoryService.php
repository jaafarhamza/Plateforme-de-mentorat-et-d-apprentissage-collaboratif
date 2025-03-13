<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryService{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    
    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    public function getCategoriesParent($id)
    {
        return $this->categoryRepository->getParentsWithChildren($id);
    }

    public function getCategoryById($id)
    {
        return $this->categoryRepository->find($id);
    }


    public function createCategory(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        return $this->categoryRepository->create($data);
    }

    public function updateCategory($id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory($id)
    {
        return $this->categoryRepository->delete($id);
    }

    
    
}