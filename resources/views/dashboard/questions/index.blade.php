@extends('dashboard.layouts.dashboard')
@section('title', 'Dashboard - Questions')
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Questions</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="card">
        <a href="{{ route('questions.create') }}" class="btn btn-primary mt-4 mx-4 mb-4" style="width: 100px;">Add</a>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($questions as $question)
                        <tr>
                            <td>{{ $question->question }}</td>
                            <td>{{ $question->answer }}</td>
                            <td>
                                <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        class="avatar avatar-xs pull-up">
                                        @if ($question->is_active == 1)
                                            <span class="badge bg-label-success me-1">Active</span>
                                        @else
                                            <span class="badge bg-label-danger me-1">Inactive</span>
                                        @endif
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow shadow-none"
                                        data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-18px"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('questions.edit', $question->id) }}">
                                            <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>
                                            Edit</a>
                                        <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this question?');">
                                                <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>
                                                Delete
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




@endsection



@push('scripts')
@endpush
