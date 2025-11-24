<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled', 'Rescheduled'];
        $paymentMethods = ['PayPal', 'Stripe', 'Cash'];
        $paymentStatuses = ['Pending', 'Processing', 'Paid', 'Failed', 'Cancelled', 'Refunded'];

        $bookings = [];

        for ($i = 1; $i <= 20; $i++) {

            $date = Carbon::now()->subDays(rand(0, 30));
            $time = Carbon::createFromTime(rand(8, 20), rand(0, 1) ? 0 : 30);

            $bookings[] = [
                'id' => $i,
                'patient_id' => rand(1, 10),     // لازم يكون عندك مرضى بهذا الرينج
                'doctor_id' => rand(1, 10),      // لازم يكون عندك دكاترة بهذا الرينج
                'booking_date' => $date->toDateString(),
                'booking_time' => $time->toTimeString(),

                'status' => $statuses[array_rand($statuses)],
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],

                'stripe_session_id' => rand(0, 1) ? 'sess_' . bin2hex(random_bytes(5)) : null,
                'stripe_payment_intent' => rand(0, 1) ? 'pi_' . bin2hex(random_bytes(5)) : null,

                'created_at' => now()->subDays(rand(1, 10)),
                'updated_at' => now()->subDays(rand(0, 5)),
                'deleted_at' => null,
            ];
        }

        DB::table('bookings')->insert($bookings);

    }
}
