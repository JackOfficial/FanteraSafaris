@extends('layouts.auth')

@section('title', 'Reset Password | FameOceans')

@section('content')
<div class="auth-page container-fluid">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">

            <!-- Logo -->
            <div class="text-center my-4">
                <a href="/"><img src="{{ asset('images/FameOceans.png') }}"
                     alt="FameOceans Logo"
                     style="width: 180px;"></a>
            </div>

            <!-- Glass Card -->
            <div class="card glass-card">
                <div class="card-header">
                    Reset Your Password
                </div>

                <div class="card-body">

                    <p class="mb-3 text-center text-muted">
                        Enter your new password below.
                    </p>

                    <!-- Status message -->
                    @if (session('status'))
                        <div class="alert alert-success small">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf

                        <!-- Hidden token -->
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <!-- Email -->
    <div class="input-group mb-3 d-none">
        <input type="email"
               name="email"
               value="{{ old('email', request('email')) }}"
               class="form-control"
               required
               autofocus>
    </div>

                        <!-- Password -->
                        <div class="input-group mb-3" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="New password"
                                   required>
                            <span class="input-group-text" @click="show = !show" style="cursor:pointer">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-warning small mb-2">
                                {{ $message }}
                            </div>
                        @enderror

                        <!-- Password Confirmation -->
                        <div class="input-group mb-4" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   placeholder="Confirm password"
                                   required>
                            <span class="input-group-text" @click="show = !show" style="cursor:pointer">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <div class="text-warning small mb-2">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="text-center mb-3">
                            <button type="submit" class="btn btn-ocean w-50 py-2">
                                Reset Password
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-2">
                        <a href="/login" class="fw-semibold">Back to Login</a>
                    </div>

                </div>
            </div>

            <div class="auth-footer text-center text-muted mt-4">
                © {{ date('Y') }} FameOceans ·
                <a href="/privacy-policy">Privacy</a> ·
                <a href="/terms">Terms</a>
            </div>

        </div>
    </div>
</div>
@endsection