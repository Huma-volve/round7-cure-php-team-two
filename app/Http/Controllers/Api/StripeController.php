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
        $payload = $request->getContent();
        $event = json_decode($payload);

        Log::info('Stripe webhook received: ' . $payload);

        if (isset($event->type) && $event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $bookingId = $session->metadata->booking_id ?? null;

            if ($bookingId) {
                $booking = Booking::find($bookingId);

                if ($booking) {
                    $booking->update([
                        'status' => 'Confirmed',
                        'payment_status' => 'Paid',
                        'stripe_session_id' => $session->id,
                        'stripe_payment_intent' => $session->payment_intent,
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


