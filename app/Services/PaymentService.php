<?php

namespace App\Services;

use Stripe\Stripe;
use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentService
{
    public function initiateCheckout(Course $course, User $user)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $course->price * 100,
                        'product_data' => [
                            'name' => $course->title,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['courseId' => $course->id]),
                'cancel_url' => route('payment.cancel', ['courseId' => $course->id]),
            ]);

            $payment = Payment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => 'eur',
                'stripe_payment_intent' => $session->id,
                'status' => 'pending',
                'metadata' => [
                    'course_title' => $course->title
                ]
            ]);

            Cache::put(
                "checkout_session:{$session->id}", 
                $payment->id, 
                now()->addHours(1)
            );

            return [
                'checkout_url' => $session->url,
                'session_id' => $session->id
            ];
        } catch (\Exception $e) {
            Log::error('Checkout initiation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getPaymentStatus($paymentId)
    {
        try {
            $payment = Payment::getCachedPayment($paymentId);

            if ($payment->status === 'pending') {
                $this->verifyStripePaymentStatus($payment);
            }

            return $payment;
        } catch (\Exception $e) {
            Log::error('Payment status retrieval failed: ' . $e->getMessage());
            throw $e;
        }
    }
    public function getPaymentHistory(User $user)
    {
        // Cache payment history
        return Cache::remember(
            "user_payment_history:{$user->id}", 
            now()->addHours(1), 
            function () use ($user) {
                return Payment::where('user_id', $user->id)
                    ->with('course')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        );
    }
    private function verifyStripePaymentStatus(Payment $payment)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::retrieve($payment->stripe_payment_intent);

            // Update payment status
            $payment->status = $session->payment_status === 'paid' 
                ? 'completed' 
                : 'failed';
            $payment->save();

            $payment->cachePayment();
        } catch (\Exception $e) {
            Log::error('Stripe payment verification failed: ' . $e->getMessage());
        }
    }
}