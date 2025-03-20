<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function coursesStatistics()
    {
        try {
            $statistics = $this->statisticsService->getCourseStatistics();

            return response()->json([
                'status' => 'success',
                'data' => $statistics
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function categoriesStatistics()
    {
        try {
            $statistics = $this->statisticsService->getCategoryStatistics();

            return response()->json([
                'status' => 'success',
                'data' => $statistics
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tagsStatistics()
    {
        try {
            $statistics = $this->statisticsService->getTagStatistics();

            return response()->json([
                'status' => 'success',
                'data' => $statistics
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}