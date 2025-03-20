<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\TagService;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        try {
            $tags = $this->tagService->getAllTags();
            return response()->json([
                'status' => 'success',
                'data' => $tags
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            // \Log::error("Error getting tags: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tags'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $tag = $this->tagService->getTagById($id);
            return response()->json([
                'status' => 'success',
                'data' => $tag
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            // \Log::error("Error getting tag: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Tag not found'
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(Request $request)
    {
        try {
            $tag = $this->tagService->createTag($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Tag created successfully',
                'data' => $tag
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            // \Log::error("Error creating tag: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $tag = $this->tagService->updateTag($id, $request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Tag updated successfully',
                'data' => $tag
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            // \Log::error("Error updating tag: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->tagService->deleteTag($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Tag deleted successfully'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            // \Log::error("Error deleting tag: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}