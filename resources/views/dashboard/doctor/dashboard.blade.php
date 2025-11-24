{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('dashboard.layouts.dashboard')

@section('title', 'Doctor Dashboard')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-dashboard.css') }}">
@endpush

@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('doctor.dashboard') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="from" class="form-control"
                        value="{{ request('from', $totals['range']['from']) }}">
                </div>
                <div class="col-auto">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="to" class="form-control"
                        value="{{ request('to', $totals['range']['to']) }}">
                </div>
                <div class="col-auto align-self-end">
                    <button class="btn btn-primary">Apply</button>
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Bookings</small>
                    <h3 class="mt-2">{{ number_format($totals['total_bookings'] ?? 0) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Paid Bookings</small>
                    <h3 class="mt-2">{{ number_format($totals['total_payments'] ?? 0) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Earnings</small>
                    <h3 class="mt-2">{{ number_format($totals['total_earnings'] ?? 0, 2) }}
                        {{ config('app.currency', 'EGP') }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Patients</small>
                    <h3 class="mt-2">{{ number_format($totals['unique_patients'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings Trend</h5>
                    <div id="doctorBookingsChart" style="min-height:300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue Trend</h5>
                    <div id="doctorRevenueChart" style="min-height:300px"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- bookings by status --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Bookings by Status</h5>
            <div class="row">
                @foreach ($byStatus as $status => $count)
                    <div class="col-md-3 col-6 mb-2">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="small text-muted">{{ $status }}</div>
                                <h4 class="mt-1">{{ $count }}</h4>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if ($byStatus->isEmpty())
                    <div class="text-muted">No bookings found in range.</div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = @json($bookingsTrend['labels'] ?? []);
            const bookingsSeries = @json($bookingsTrend['series'] ?? []);
            const revenueLabels = @json($revenueTrend['labels'] ?? []);
            const revenueSeries = @json($revenueTrend['series'] ?? []);

            var options1 = {
                series: [{
                    name: 'Bookings',
                    data: bookingsSeries
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                xaxis: {
                    categories: labels
                },
                stroke: {
                    curve: 'smooth'
                },
                dataLabels: {
                    enabled: false
                }
            };
            new ApexCharts(document.querySelector("#doctorBookingsChart"), options1).render();

            var options2 = {
                series: [{
                    name: 'Revenue',
                    data: revenueSeries
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: {
                        show: false
                    }
                },
                xaxis: {
                    categories: revenueLabels
                },
                dataLabels: {
                    enabled: false
                }
            };
            new ApexCharts(document.querySelector("#doctorRevenueChart"), options2).render();
        });
    </script>
@endpush
