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
                        <!-- Content -->
                         <!-- Striped Rows -->
              <div class="card">
                <h5 class="card-header">Bookings</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Patient Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Payment Amount</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($bookings as $booking )
                      <tr>
                        <td>
                            <div class="avatar avatar-online">
                                <img src={{$booking->patient->user->profile_photo}} alt="avatar" class="rounded-circle" />
                                <span>{{$booking->patient->user->name}}</span>
                            </div>
                        </td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->booking_time }}</td>
                        <td>
                          <span class="badge rounded-pill bg-label-primary me-1">{{ $booking->status }}</span>
                        </td>
                        <td>{{ $booking->doctor->session_price }}</td>
                        <td>
                          <div class="dropdown">
                            <button
                              type="button"
                              class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                              data-bs-toggle="dropdown">
                              <i class="icon-base ri ri-more-2-line icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="{{ route('bookings.show', $booking->id) }}">
                                <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                View Booking</a
                              >
                              <form method="POST" action="{{ route('doctor.bookings.cancel', $booking->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                    Cancel Booking
                                </button>
                            </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Striped Rows -->

            {{-- / Layout page --}}

        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('dashboard.partials.scripts')
    @stack('scripts')
</body>

</html>
