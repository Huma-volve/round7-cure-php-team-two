<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Booking;
use App\Models\Patient;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];
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
        //$this->registerPolicies();

        Gate::define('isDoctor', function ($user) {
            // لو بتستخدم Spatie roles
            if (method_exists($user, 'hasRole')) {
                return $user->hasRole('doctor');
            }

            // أو ببساطة لو عنده علاقة doctor
            //return (bool) $user->doctor;
            return true;
        });
        /*
        Gate::define('view-patient', function ($user, Patient $patient) {
            // يتحقق إن المستخدم طبيب وله حجز مع المريض ده
            if ($user->doctor) {
                return Booking::where('doctor_id', $user->doctor->id)
                    ->where('patient_id', $patient->id)
                    ->exists();
            }

            return false;
        }); 
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return true;
            }
        });
*/
    }
}
