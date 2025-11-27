@extends('dashboard.layouts.dashboard')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <div class="row gy-6">
        <div class="col-12">
            <h3>Doctor Dashboard</h3>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Available Times</h5>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="ri-add-line"></i> Add New Time
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                <th>Date</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse ($availableTimes??[] as $timeString)
                                @php
                                    $date = \Carbon\Carbon::parse($timeString);
                                @endphp

                                <tr>
                                    <td class="fw-bold">{{ $date->format('l') }}</td>
                                    <td>{{ $date->format('Y-m-d') }}</td>
                                    <td><span class="badge bg-label-info">{{ $date->format('h:i A') }}</span></td>
                                    <td><span class="badge bg-label-info">{{ $date->copy()->addHour()->format('h:i A') }}</span></td>
                                    <td><span class="badge bg-success">Available</span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Edit Button --}}
                                            <a href="javascript:void(0);"
                                               class="btn btn-sm btn-icon btn-outline-primary edit-btn"
                                               data-time="{{ $timeString }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editModal"
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            {{-- 👇 1. زرار الحذف الجديد (شيلنا الفورم من هنا) --}}
                                            <button type="button"
                                                    class="btn btn-sm btn-icon btn-outline-danger delete-btn"
                                                    data-time="{{ $timeString }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    title="Delete">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="ri-calendar-close-line fs-3 d-block mb-2"></i>
                                        No available times found.
                                    </td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Time Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.add_slot') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Date & Time</label>
                            <input
                                type="datetime-local"
                                name="time"
                                class="form-control @error('time') is-invalid @enderror"
                                value="{{ old('time') }}"
                                required
                            >
                            @error('time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Time</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Time Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.update_slot') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <input type="hidden" name="old_time" id="old_time_input" value="{{ old('old_time') }}">
                        <div class="mb-3">
                            <label class="form-label">Pick New Date & Time</label>
                            <input
                                type="datetime-local"
                                name="new_time"
                                class="form-control @error('new_time') is-invalid @enderror"
                                value="{{ old('new_time') }}"
                                required
                            >
                            @error('new_time')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 👇 2. DELETE MODAL (المودال الجديد للحذف) --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('doctor.delete_slot') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body text-center">
                        {{-- أيقونة تحذير --}}
                        <i class="ri-error-warning-line text-danger" style="font-size: 3rem;"></i>
                        <h4 class="mt-2">Are you sure?</h4>
                        <p class="text-muted">You won't be able to revert this!</p>

                        {{-- هنا هنحط قيمة الوقت اللي هنمسحه بالجافاسكريبت --}}
                        <input type="hidden" name="time" id="delete_time_input">
                    </div>

                    <div class="modal-footer justify-content-center">
                        {{-- زرار الإلغاء --}}
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            No, Cancel
                        </button>

                        {{-- زرار الحذف --}}
                        <button type="submit" class="btn btn-danger">
                            Yes, Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. كود الـ Edit
            const editBtns = document.querySelectorAll('.edit-btn');
            const oldTimeInput = document.getElementById('old_time_input');

            editBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const time = this.getAttribute('data-time');
                    oldTimeInput.value = time;
                });
            });

            // 👇 2. كود الـ Delete الجديد
            const deleteBtns = document.querySelectorAll('.delete-btn');
            const deleteTimeInput = document.getElementById('delete_time_input');

            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    // بناخد الوقت من الزرار اللي اتداس عليه
                    const time = this.getAttribute('data-time');
                    // ونحطه في الانبوت المخفي جوه مودال الحذف
                    deleteTimeInput.value = time;
                });
            });

            // Open modals if errors exist
            @if($errors->has('new_time'))
            new bootstrap.Modal(document.getElementById('editModal')).show();
            @endif

            @if($errors->has('time'))
            new bootstrap.Modal(document.getElementById('addModal')).show();
            @endif
        });
    </script>
@endsection
