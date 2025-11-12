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
                        <form action="{{ route('settings.update', $settings->id) }}" method="post" autocomplete="off"
                            enctype="multipart/form-data">
                            @method('PUT')
                            @csrf
                            <div class="card-header">
                                <h3 class="card-title">Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="phone">Phone</label>
                                        <input name="phone" type="number" class="form-control" id="phone"
                                            value="{{ isset($settings) ? $settings->phone : '' }}">
                                    </div>

                                    <div class="form-group col-6 mb-3">
                                        <label for="email">Email</label>
                                        <input name="email" class="form-control" id="email"
                                            value="{{ isset($settings) ? $settings->email : '' }}">
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="logo">Logo</label>
                                        <input name="logo" type="file" class="form-control image-input m-2"
                                            data-target="image-preview-3" id="logo">

                                        <div class="form-group col-6">
                                            <img src="{{ isset($settings) && $settings->logo ? url($settings->logo) : asset('default-logo.png') }}"
                                                style="width: 50px" id="image-preview-3" alt="">
                                        </div>
                                    </div>
                                </div>


                                <div class="card-footer mb-3">
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </div>
                            </div>
                            <!-- /.card-body -->
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
