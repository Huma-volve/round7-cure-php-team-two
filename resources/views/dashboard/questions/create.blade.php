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
                        <form action="{{ route('questions.store') }}" method="post" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">Add Questions & Answers</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Question</label>
                                        <input name="question" type="text" class="form-control" id="exampleInputName1"
                                            value="{{ old('question') }}" placeholder="Add Question">
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Answer</label>
                                        <textarea name="answer" class="form-control" id="exampleInputName1" placeholder="Add Answer">{{ old('answer') }}</textarea>
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="exampleInputName1">Active</label>
                                        <select name="is_active" class="form-control">
                                            <option selected disabled>Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>

                                </div>
                                <hr>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Add</button>
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
