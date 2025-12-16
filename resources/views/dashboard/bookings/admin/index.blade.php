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
                <!--search form-->
                <div class="col-md">  <!-- half width on desktop -->
                    <div class="card">
                        <h5 class="card-header">Search</h5>
                        <div class="card-body">

                            <form action="{{ route('bookings.index') }}" method="GET" class="p-0 m-0">
                                <div class="form-floating form-floating-outline">

                                    <input
                                        type="search"
                                        name="search"
                                        class="form-control search-small"
                                        id="floatingInput"
                                        placeholder="John Doe"
                                    />

                                    <div id="floatingInputHelp" class="form-text">
                                        Search by Patient or Doctor Name
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <!--search form-->
                <h5 class="card-header">Bookings</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>Patient Name</th>
                        <th>Doctor Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Payment Amount</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($bookings as $index=>$booking )
                      <tr>
                        <td>{{ $index+1 }}</td>
                        <td>
                            <div class="avatar avatar-online">
                                <img src={{$booking->patient->user->profile_photo}} alt="avatar" class="rounded-circle" />
                                <span>{{$booking->patient->user->name}}</span>
                            </div>
                        </td>
                        <td>
                            <div class="avatar avatar-online">
                                <img src={{$booking->doctor->user->profile_photo}} alt="avatar" class="rounded-circle" />
                                <span>{{$booking->doctor->user->name}}</span>
                            </div>
                          </td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->booking_time }}</td>
                        <td>
                          <span class="badge rounded-pill bg-label-primary me-1">{{ $booking->status }}</span>
                        </td>
                        <td>{{ $booking->total}}</td>
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
                            </div>
                          </div>
                        </td>
                      </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              {{ $bookings->links() }}
              <!--/ Striped Rows -->

            {{-- / Layout page --}}

        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('dashboard.partials.scripts')
    @stack('scripts')
</body>

</html>
