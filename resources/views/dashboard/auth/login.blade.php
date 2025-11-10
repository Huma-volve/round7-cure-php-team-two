@extends('dashboard.layouts.auth')

@section('title', 'Login')

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
            <h4 class="mb-1">Welcome Back 👋🏻</h4>
            <p class="mb-5">Please sign in to continue</p>

            <form method="POST" action="{{ route('login') }}" class="mb-5">
                @csrf
                <div class="form-floating form-floating-outline mb-5">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                        autofocus required />
                    <label for="email">Email</label>
                </div>

                <div class="mb-5">
                    <div class="form-password-toggle form-control-validation">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="********" required />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i
                                    class="icon-base ri ri-eye-off-line icon-20px"></i></span>
                        </div>
                    </div>
                </div>

                <div class="mb-5 d-flex justify-content-between align-items-center">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="float-end">Forgot password?</a>
                </div>

                <button class="btn btn-primary d-grid w-100 mb-3" type="submit">Login</button>
            </form>

            <p class="text-center mb-0">
                <span>New here?</span>
                <a href="{{ route('register') }}"><span>Create an account</span></a>
            </p>
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
