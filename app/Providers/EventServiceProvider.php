<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{


    protected $listen = [
    \App\Events\PaymentCompleted::class => [
        \App\Listeners\SendPaymentNotification::class,
    ],

    \App\Events\BookingConfirmed::class => [
        \App\Listeners\SendBookingConfirmationNotification::class,
    ],

    \App\Events\BookingStatusChanged::class => [
        \App\Listeners\SendBookingStatusNotification::class,
    ],
];
    /**
     * Register services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
