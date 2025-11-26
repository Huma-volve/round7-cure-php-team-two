@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{-- 1. تعديل العنوان --}}
                        <h5 class="mb-0">Edit Helper: <span class="text-primary">{{ $user->name }}</span></h5>
                    </div>

                    <div class="card-body">
                        {{-- 2. تعديل الراوت ليناسب الهيلبر --}}
                        <form action="{{ route('admin.helper.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row">
                                {{-- 1. Name --}}
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           placeholder="Helper's Name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 2. Email --}}
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email"
                                           value="{{ old('email', $user->email) }}"
                                           placeholder="helper@example.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 3. Phone --}}
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    {{-- لاحظ هنا استخدمت phone بدلا من phone_number لتتوافق مع الجدول السابق، لو عندك اسمها phone_number عدلها --}}
                                    <input maxlength="11" type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone"
                                           value="{{ old('phone', $user->phone_number) }}"
                                           placeholder="01xxxxxxxxx" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- ⚠️ تم حذف التخصص (Specialty) --}}
                                {{-- ⚠️ تم حذف رقم الرخصة (License) --}}
                                {{-- ⚠️ تم حذف السعر (Price) --}}

                                {{-- 4. Password (Optional) --}}
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current)</small></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-3 col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save Changes</button>
                                {{-- 3. تعديل زرار الإلغاء --}}
                                <a href="{{ route('admin.helper.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
