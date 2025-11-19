<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function checkout(Request $request,$bookingId)
{

    $booking = Booking::with('doctor')->findOrFail($bookingId);
    $price=$booking->doctor->session_price;
    Stripe::setApiKey(env('STRIPE_SECRET'));
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'egp',
                'product_data' => ['name' => 'Booking with Dr. ' . $booking->doctor->user->name],
                'unit_amount' => $price * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('stripe.success', ['booking' => $booking->id]),
        'cancel_url' => route('stripe.cancel', ['booking' => $booking->id]),
        'metadata' => [
            'booking_id' => $booking->id,
        ],
    ]);

    return response()->json(['url' => $session->url]);
}
public function handleWebhook(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $payload = $request->getContent();
    $sigHeader = $request->header('Stripe-Signature');
    $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
    } catch (\UnexpectedValueException $e) {
        return response('Invalid payload', 400);
    } catch (\Stripe\Exception\SignatureVerificationException $e) {
        return response('Invalid signature', 400);
    }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        $bookingId = $session->metadata->booking_id ?? null;

        // ✅ Retrieve PaymentIntent to get payment time
        $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);

        // Usually one charge per payment
        $charge = $paymentIntent->charges->data[0] ?? null;
        $paymentTime = $charge ? date('Y-m-d H:i:s', $charge->created) : null;

        if ($bookingId) {
            $booking = Booking::find($bookingId);

            if ($booking) {
                $booking->update([
                    'status' => 'Confirmed',
                    'payment_status' => 'Paid',
                    'stripe_session_id' => $session->id,
                    'stripe_payment_intent' => $session->payment_intent,
                    'payment_time' => $paymentTime,
                ]);

                Log::info('✅ Booking payment confirmed: ' . $bookingId);
            } else {
                Log::warning('⚠ Booking not found: ' . $bookingId);
            }
        } else {
            Log::warning('⚠ No booking_id in session metadata');
        }
    }

    return response('Webhook received', 200);
}

}


