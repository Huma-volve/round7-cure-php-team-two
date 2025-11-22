@extends('dashboard.layouts.dashboard')

@section('content')
    {{-- (مهم): اتأكد إن دول موجودين في الـ layout الرئيسي
         أو سيبهم هنا لو هتستخدم الكومبوننت في الصفحة دي بس --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>


    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header">Profile Details</h5>
                @if(session('success')) <div class="alert alert-success mx-4">{{ session('success') }}</div> @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- 1. الكومبوننت بتاع الصورة (زي ما هو) --}}
                        <x-image
                            :currentImageUrl="$doctor->profile_photo ? asset('storage/images/users/' . $doctor->profile_photo) : null"
                            :defaultName="$doctor->name"
                        />

                        {{-- 👇👇👇 (التعديل الجديد) 👇👇👇 --}}

                        {{-- (اتمسح): الـ row بتاع الحقول القديم --}}

                        {{-- 2. استدعاء كومبوننت الحقول الأساسية --}}
                        {{-- إحنا بنبعتله الـ doctor وهو هيعتبره الـ user جوه الكومبوننت --}}
                        {{-- وخلينا isUpdate بـ true عشان الباسوورد يبقى اختياري --}}

                        <x-user-fields :user="$doctor" :isUpdate="true">


                            {{-- 1. License Number --}}
                            <div class="mb-3 col-md-6">
                                <label class="form-label">License Number</label>
                                <input class="form-control" type="text" name="license_number" value="{{ old('license_number', $doctor->license_number ?? '') }}" />
                            </div>

                            {{-- 2. Session Salary --}}
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Session Price</label>
                                <input class="form-control"  name="session_price" value="{{ old('session_price', $doctor->session_price?? '') }}" />
                            </div>
                        </x-user-fields>



                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
