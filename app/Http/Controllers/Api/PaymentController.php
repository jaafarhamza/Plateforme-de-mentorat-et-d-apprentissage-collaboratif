<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function checkout(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'course_id' => 'required|exists:courses,id'
            ]);

            // Get the course
            $course = Course::findOrFail($request->input('course_id'));

            // Get current authenticated user
            $user = auth::user();

            // Initiate checkout
            $checkoutData = $this->paymentService->initiateCheckout($course, $user);

            return response()->json([
                'status' => 'success',
                'data' => $checkoutData
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function status($paymentId)
    {
        try {
            $payment = $this->paymentService->getPaymentStatus($paymentId);

            return response()->json([
                'status' => 'success',
                'data' => $payment
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function history()
    {
        try {
            // Get current authenticated user
            $user = auth::user();

            // Get payment history
            $payments = $this->paymentService->getPaymentHistory($user);

            return response()->json([
                'status' => 'success',
                'data' => $payments
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}