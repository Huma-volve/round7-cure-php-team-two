@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <div class="card p-sm-7 p-2">
        <div class="app-brand justify-content-center mt-5">
            <a href="{{ url('/') }}" class="app-brand-link gap-3">
                <span class="app-brand-logo demo text-primary">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="28">
                </span>
                <span class="app-brand-text demo text-heading fw-semibold">{{ config('dashboard.title') }}</span>
            </a>
        </div>

        <div class="card-body mt-1">
            <h4 class="mb-1">Forgot Password? 🔒</h4>
            <p class="mb-5">Enter your email and we'll send instructions to reset your password.</p>

            <form method="POST" action="{{ route('password.email') }}" class="mb-5">
                @csrf
                <div class="form-floating form-floating-outline mb-5">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                        required />
                    <label for="email">Email</label>
                </div>
                <button class="btn btn-primary d-grid w-100 mb-3">Send Reset Link</button>
            </form>

            <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                    <i class="icon-base ri ri-arrow-left-s-line icon-20px me-1_5"></i>
                    Back to login
                </a>
            </div>
        </div>
    </div>

    {{-- Background illustrations --}}
    <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree"
        class="authentication-image-object-left d-none d-lg-block" />
    <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}"
        class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
    <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree"
        class="authentication-image-object-right d-none d-lg-block" />
@endsection
