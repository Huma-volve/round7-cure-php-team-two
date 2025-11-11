@extends('dashboard.layouts.dashboard')

@section('title', 'Dashboard')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-dashboard.css') }}">
@endpush

@section('content')
    <div class="row">
        {{-- Summary cards --}}
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Bookings ({{ $from->format('Y-m-d') }} → {{ $to->format('Y-m-d') }})</small>
                    <h3 class="mt-2">{{ $bookingsCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Revenue</small>
                    <h3 class="mt-2">{{ number_format($revenue, 2) }} {{ config('app.currency', 'EGP') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Patients</small>
                    <h3 class="mt-2">{{ $patientsCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Latest Booking</small>
                    <h3 class="mt-2">
                        {{ optional($latestBookings->first())->starts_at ? \Carbon\Carbon::parse(optional($latestBookings->first())->starts_at)->format('Y-m-d H:i') : '—' }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Chart --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings Trend</h5>
                    <div id="bookingsChart"></div>
                </div>
            </div>
        </div>

        {{-- Latest payments --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Latest Payments</h5>
                    <ul class="list-unstyled">
                        @foreach ($latestPayments as $p)
                            <li class="mb-2">
                                <strong>{{ number_format($p->amount, 2) }}</strong>
                                <div class="small text-muted">
                                    {{ optional($p->paid_at)->format('Y-m-d H:i') ?? optional($p->created_at)->format('Y-m-d') }}
                                </div>
                            </li>
                        @endforeach
                        @if ($latestPayments->isEmpty())
                            <li class="text-muted">No payments yet.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest bookings table --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Latest Bookings</h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Starts At</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBookings as $b)
                            <tr>
                                <td>{{ $b->patient->user->name ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($b->starts_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ ucfirst($b->status) }}</td>
                                <td>{{ number_format(optional($b)->price ?? 0, 2) }}</td>
                                <td><a href="{{ route('doctor.patients.show', $b->patient) }}"
                                        class="btn btn-sm btn-outline-primary">View Patient</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">No bookings found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($labels);
            const series = @json($series);

            var options = {
                series: [{
                    name: 'Bookings',
                    data: series
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        rotate: -45
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: false
                },
                yaxis: {
                    min: 0
                },
            };

            var chart = new ApexCharts(document.querySelector("#bookingsChart"), options);
            chart.render();
        });
    </script>
@endpush
