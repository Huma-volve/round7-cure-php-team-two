@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- 👇 لينك الأيقونات --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">Helpers List</h4>
            <a href="{{ route('admin.helper.add') }}" class="btn btn-primary">
                <i class="ri-user-add-line me-1"></i> Add New Helper
            </a>
        </div>

        {{-- Table Card --}}
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        {{-- 1. Helper Info (Name + Email + Avatar) --}}
                        <th>Helper</th>
                        {{-- 2. Phone --}}
                        <th>Phone</th>
                        {{-- 3. Date Joined --}}
                        <th>Date Joined</th>
                        {{-- 4. Actions --}}
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                    @forelse($helpers ?? [] as $helper)
                        <tr>
                            {{-- 1. Basic Info: Avatar, Name, Email --}}
                            <td>
                                <div class="d-flex justify-content-start align-items-center user-name">
                                    <div class="avatar-wrapper">
                                        <div class="avatar me-3">
                                            <img src="{{ $helper->user?->profile_photo ? asset('storage/images/users/'.$helper->user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($helper->user?->name ?? 'Unknown').'&background=random' }}"
                                                 alt="Avatar" class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        {{-- الاسم --}}
                                        <span class="fw-medium">{{ $helper->user?->name ?? 'Unknown User' }}</span>
                                        {{-- الإيميل تحته --}}
                                        <small class="text-muted">{{ $helper->user?->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Phone --}}
                            <td>
                                <span class="fw-light">{{ $helper->user?->phone ?? 'N/A' }}</span>
                            </td>

                            {{-- 3. Date Joined (Created At) --}}
                            <td>
                                <span class="fw-bold">{{ $helper->created_at ? $helper->created_at->format('Y-m-d') : '-' }}</span>
                            </td>

                            {{-- 4. Actions --}}
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.helper.update-page', $helper->user_id) }}"
                                       class="btn btn-sm btn-primary"
                                       title="Edit">
                                        <i class="ri-pencil-line"></i>
                                    </a>

                                    <form action="{{ route('admin.helper.delete', $helper->user_id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this helper?');">
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
                        {{-- Empty State --}}
                        <tr>
                            {{-- 👇 خليناها 4 أعمدة بس عشان شلنا عمود الوظيفة --}}
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                    <div class="bg-label-secondary rounded-circle p-3 mb-3">
                                        <i class="ri-user-search-line fs-1"></i>
                                    </div>
                                    <h5 class="mb-1">No Helpers Found</h5>
                                    <p class="mb-0 small">Start by adding a new helper to the system.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="card-footer border-top-0">
                @if(isset($helpers) && count($helpers) > 0)
                    {{ $helpers->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
