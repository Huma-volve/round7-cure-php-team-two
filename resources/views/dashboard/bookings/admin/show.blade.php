<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('dashboard.title') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
    @include('dashboard.partials.styles')
    @stack('head')
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            {{-- Sidebar --}}
            @include('dashboard.partials.sidebar')
            {{-- Layout container --}}
            <div class="layout-page">
                {{-- Navbar --}}
                @include('dashboard.partials.navbar')

                {{-- Content wrapper --}}
                <div class="content-wrapper">
                    {{-- Main content --}}
                    <div class="container-xxl flex-grow-1 container-p-y">
                      <div class="col-md-6 col-lg-4">
                        <h6 class="mt-2 text-body-primary">{{ucwords($booking->patient->user->name)}} with Doctor {{ucwords($booking->doctor->user->name)}} Booking</h6>
                        <div class="card">
                          <div class="card-body">
                            <h5 class="card-title">Booking Date</h5>
                            <p class="card-text">{{ $booking->booking_date->format('d M Y') }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Booking Time</h5>
                            <p class="card-text">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g') }} and half {{ \Carbon\Carbon::parse($booking->booking_time)->format('a') }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Booking Status</h5>
                            <p class="card-text">{{ $booking->status }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Payment Method</h5>
                            <p class="card-text">{{ $booking->payment_method }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Payment Status</h5>
                            <p class="card-text">{{ $booking->payment_status }}</p>
                          </div>
                          <div class="card-body">
                            @php
                            if($booking->payment_time === null) {
                                $text = "No Payment Time Available";
                            } else {
                                $time = $booking->payment_time; // Carbon instance
                                $hour = $time->format('g');     // 4
                                $period = $time->format('a');   // pm
                                $date = $time->format('d F Y'); // 13 November 2025

                                $text = $time->minute == 30
                                    ? "$hour and half $period on $date"
                                    : $time->format('g:i a \o\n d F Y');
                            }
                          @endphp
                            <h5 class="card-title">Payment Time</h5>
                            <p class="card-text">{{$text}}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Paid Amount</h5>
                            <p class="card-text">{{ $booking->total }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Doctor Amount</h5>
                            <p class="card-text">{{ $booking->doctor_amount }}</p>
                          </div>
                          <div class="card-body">
                            <h5 class="card-title">Admin Amount</h5>
                            <p class="card-text">{{ $booking->rate }}</p>
                          </div>
                        </div>
                      </div>
                     </div>
                    <div class="layout-overlay layout-menu-toggle"></div>
                    </div>

  @include('dashboard.partials.scripts')
  @stack('scripts')
</body>

</html>
