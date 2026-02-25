@extends('layouts.auth')

@section('title', 'Forgot Password | FameOceans')

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
                    Forgot Your Password?
                </div>

                <div class="card-body">

                    <p class="mb-3 text-center text-muted">
                        Enter your email address below and we will send you a link to reset your password.
                    </p>

                    <!-- Success message -->
                    @if (session('status'))
                        <div class="alert alert-success small">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="/forgot-password">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Email address"
                                   required
                                   autofocus>
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>

                        @error('email')
                            <div class="text-warning small mb-2">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="text-center mb-3">
                            <button type="submit" class="btn btn-ocean w-50 py-2">
                                Send Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-2">
                        <a href="/login">Back to Login</a>
                    </div>

                </div>
            </div>

             <div class="auth-footer">
                © {{ date('Y') }} FameOceans ·
                <a href="/privacy-policy">Privacy</a> ·
                <a href="/terms">Terms</a>
            </div>

        </div>
    </div>
</div>
@endsection