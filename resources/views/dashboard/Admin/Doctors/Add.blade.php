@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Add New Doctor</h5>

                    <div class="card-body">
                        {{-- Ensure route 'admin.add-doctor' exists in web.php --}}
                        <form action="{{ route('admin.doctor.add-page') }}" method="POST">
                            @csrf

                            <div class="row">
                                {{-- 1. Name --}}
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" placeholder="Doctor's Name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 2. Email --}}
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" placeholder="doctor@example.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 3. Phone --}}
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone_number" value="{{ old('phone_number') }}" placeholder="01xxxxxxxxx" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 4. Specialty (Select Box) --}}
                                <div class="mb-3 col-md-6">
                                    <label for="specialty_id" class="form-label">Specialty</label>
                                    <select id="specialty_id" name="specialty_id" class="form-select @error('specialty_id') is-invalid @enderror" required>
                                        <option value="">Select Specialty</option>
                                        {{-- Loop through specialties passed from controller --}}
                                        @foreach($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialty_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 5. License Number (NEW) --}}
                                <div class="mb-3 col-md-6">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                           id="license_number" name="license_number" value="{{ old('license_number') }}" placeholder="Enter License Number" required>
                                    @error('license_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- 6. Session Price (NEW) --}}
                                <div class="mb-3 col-md-6">
                                    <label for="session_price" class="form-label">Session Price</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('session_price') is-invalid @enderror"
                                               id="session_price" name="session_price" value="{{ old('session_price') }}" placeholder="100.00" required>
                                        @error('session_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                {{-- 7. Password --}}
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Confirm Password --}}
                                <div class="mb-3 col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required>
                                </div>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Save Data</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
