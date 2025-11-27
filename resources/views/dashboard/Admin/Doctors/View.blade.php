@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- 👇 لينك الأيقونات --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Doctors List</h4>
            <a href="{{ route('admin.doctor.add') }}" class="btn btn-primary">
                <i class="ri-user-add-line me-1"></i> Add New Doctor
            </a>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Doctor</th>
                        <th>Specialty</th>
                        <th>License No.</th>
                        <th>Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                    {{-- 👇 التعديل هنا: استخدمنا forelse وزودنا ?? [] للأمان --}}
                    @forelse($doctors ?? [] as $doctor)
                        <tr>
                            {{-- 1. Doctor Info --}}
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-3">
                                            <img src="{{ $doctor->user?->profile_photo ? asset('storage/images/users/'.$doctor->user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($doctor->user?->name ?? 'Unknown').'&background=random' }}"
                                                 alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $doctor->user?->name ?? 'Unknown / Deleted User' }}</span>
                                        <small class="text-muted">{{ $doctor->user?->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Specialty --}}
                            <td>
                                @if($doctor->specialty)
                                    <span class="badge bg-label-info">{{ $doctor->specialty->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- 3. License --}}
                            <td>
                                <span class="fw-light">{{ $doctor->license_number ?? 'N/A' }}</span>
                            </td>

                            {{-- 4. Price --}}
                            <td>
                                @if($doctor->session_price)
                                    <span class="fw-bold">${{ $doctor->session_price }}</span>
                                @else
                                    <span class="badge bg-label-success">Free</span>
                                @endif
                            </td>

                            {{-- 5. Actions --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.doctor.update-page', $doctor->user_id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>

                                    <form action="{{ route('admin.doctor.delete', $doctor->user_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Delete">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                    @empty
                        {{-- 👇 دي الحالة اللي هتظهر لو مفيش دكاترة أو المتغير null --}}
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                    <div class="bg-label-secondary rounded-circle p-3 mb-3">
                                        <i class="ri-user-search-line fs-1"></i>
                                    </div>
                                    <h5 class="mb-1">No Doctors Found</h5>
                                    <p class="mb-0 small">Start by adding a new doctor to the system.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer border-top-0">
                {{-- برضه هنا نتأكد إن المتغير مش null قبل ما ننده links --}}
                @if(isset($doctors) && count($doctors) > 0)
                    {{ $doctors->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
