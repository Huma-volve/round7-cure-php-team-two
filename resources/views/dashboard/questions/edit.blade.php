@extends('dashboard.layouts.dashboard')
{{-- @section('title', 'Dashboard - Questions') --}}
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

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('questions.update',$questions->id) }}" method="post" autocomplete="off"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">Edit Questions & Answers</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Question</label>
                                        <input name="question" type="text" class="form-control" id="exampleInputName1"
                                            value="{{ $questions->question }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Answer</label>
                                        <textarea name="answer" class="form-control" id="exampleInputName1">{{ $questions->answer }}</textarea>
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Active</label>
                                        <select name="is_active" class="form-control" id="exampleInputName1">
                                            <option disabled>Select Status</option>
                                            <option value="1"
                                                {{ old('is_active', $questions->is_active ?? '') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0"
                                                {{ old('is_active', $questions->is_active ?? '') == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>


                                </div>
                                <hr>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Edit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection






@push('scripts')
@endpush
