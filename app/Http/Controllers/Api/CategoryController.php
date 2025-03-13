<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        try {
            $categories = $this->categoryService->getAllCategories();
        // dd($categories);
            return response()->json([
                'status' => 'success',
                'data' => $categories
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            // \Log::error("error getting categories: " . $e->getMessage());
            return response()->json([
                'status' => false,
            ], 404);
        }
    }

    public function Parent($id)
    {
        try {
            $categories = $this->categoryService->getCategoriesParent($id);
            return response()->json([
                'status' => true,
                'data' => $categories
            ], Response::HTTP_OK);
        }
        catch (\Exception $e) {
            // \Log::error("error getting categories: " . $e->getMessage());
            return response()->json([
                'status' => false,
            ], 404);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);
            return response()->json([
                'status' => 'success',
                'data' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        try {
            $category = $this->categoryService->createCategory($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'created success',
                'data' => $category
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = $this->categoryService->updateCategory($id, $request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'update success ',
                'data' => $category
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
            return response()->json([
                'status' => 'success',
                'message' => 'remove success '
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}