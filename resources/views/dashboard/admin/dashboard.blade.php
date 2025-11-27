{{-- resources/views/admin/dashboard.blade.php --}}
@extends('dashboard.layouts.dashboard')

@section('title', 'Admin Dashboard')

@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-dashboard.css') }}">
@endpush

@section('content')

    {{-- Date filter form (reuse same snippet used before) --}}
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="from" class="form-control"
                        value="{{ request('from', $totals['range']['from']) }}">
                </div>

                <div class="col-auto">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="to" class="form-control"
                        value="{{ request('from', $totals['range']['to']) }}">
                </div>

                <div class="col-auto">
                    <label class="form-label small mb-1 d-block">Quick range</label>
                    <div class="btn-group" role="group" aria-label="Quick range">
                        <button type="submit" name="range" value="7"
                            class="btn btn-outline-secondary {{ request('range') == '7' ? 'active' : '' }}">Last 7</button>
                        <button type="submit" name="range" value="30"
                            class="btn btn-outline-secondary {{ request('range') == '30' ? 'active' : '' }}">Last
                            30</button>
                        <button type="submit" name="range" value="90"
                            class="btn btn-outline-secondary {{ request('range') == '90' ? 'active' : '' }}">Last
                            90</button>
                    </div>
                </div>

                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary cards --}}
    <div class="row mb-3">
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Bookings</small>
                    <h3 class="mt-2">{{ number_format($totals['total_bookings'] ?? 0) }}</h3>
                    <div class="small text-muted">From {{ $totals['range']['from'] ?? $from->toDateString() }} to
                        {{ $totals['range']['to'] ?? $to->toDateString() }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Payments (count)</small>
                    <h3 class="mt-2">{{ number_format($totals['total_payments'] ?? 0) }}</h3>
                    <div class="small text-muted">Paid bookings in range</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Earnings</small>
                    <h3 class="mt-2">{{ number_format($totals['total_earnings'] ?? 0, 2) }}
                        {{ config('app.currency', 'EGP') }}</h3>
                    <div class="small text-muted">Doctor share (sum)</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <small class="text-muted">Unique Patients</small>
                    <h3 class="mt-2">{{ number_format($totals['unique_patients'] ?? 0) }}</h3>
                    <div class="small text-muted">Distinct patients in range</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings Trend</h5>
                    <div id="adminBookingsChart" style="min-height:300px"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Revenue Trend</h5>
                    <div id="adminRevenueChart" style="min-height:300px"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status summary + Top doctors + Latest bookings --}}
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bookings by Status</h5>
                    <ul class="list-unstyled">
                        @forelse($byStatus as $status => $count)
                            <li class="d-flex justify-content-between align-items-center py-1">
                                <div>{{ $status }}</div>
                                <div class="badge bg-secondary">{{ $count }}</div>
                            </li>
                        @empty
                            <li class="text-muted">No bookings</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Top Doctors (by earnings)</h5>
                    <ul class="list-unstyled">
                        @foreach ($topDoctors as $t)
                            <li class="d-flex justify-content-between py-1">
                                <div>{{ optional($t->doctor)->user->name ?? 'Doctor #' . $t->doctor_id }}</div>
                                <div><strong>{{ number_format($t->total, 2) }}</strong></div>
                            </li>
                        @endforeach
                        @if ($topDoctors->isEmpty())
                            <li class="text-muted">No data</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <!--a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-primary mb-2 d-block">Full
                                Reports</a-->
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary mb-2 d-block">Manage
                        Bookings</a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary d-block">Manage
                        Payments</a>
                </div>
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

            // Bookings chart
            var options1 = {
                series: [{
                    name: 'Bookings',
                    data: bookingsSeries
                }],
                chart: {
                    type: 'area',
                    height: 320,
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
            new ApexCharts(document.querySelector("#adminBookingsChart"), options1).render();

            // Revenue chart
            var options2 = {
                series: [{
                    name: 'Revenue',
                    data: revenueSeries
                }],
                chart: {
                    type: 'bar',
                    height: 320,
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
            new ApexCharts(document.querySelector("#adminRevenueChart"), options2).render();
        });
    </script>
@endpush
