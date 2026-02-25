@extends('layouts.auth')

@section('content')

<style>
    :root {
        --ocean-blue: #0d6efd;
        --deep-ocean: #0a3d62;
        --glass-bg: rgba(255, 255, 255, 0.97);
        --glass-border: rgba(226, 232, 240, 0.9);
        --text-dark: #0f172a;
        --text-muted: #64748b;
    }

    body {
        background: linear-gradient(135deg, var(--deep-ocean), var(--ocean-blue));
        min-height: 100vh;
    }

    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .glass-card {
        background: var(--glass-bg);
        border-radius: 16px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 20px 40px rgba(0,0,0,.25);
        color: var(--text-dark);
    }

    .glass-card * {
        color: var(--text-dark);
    }

    .glass-card .card-header {
        font-weight: 700;
        font-size: 1.15rem;
        text-align: center;
        background: transparent;
        border-bottom: 1px solid #e2e8f0;
        padding: 1rem 0;
    }

    .glass-card a {
        color: var(--ocean-blue);
        font-weight: 500;
        text-decoration: none;
    }

    .glass-card a:hover {
        text-decoration: underline;
    }

    .form-control {
        background: #ffffff;
        color: var(--text-dark);
        border-radius: 8px;
        border: 1px solid #ced4da;
    }

    .form-control::placeholder {
        color: var(--text-muted);
    }

    .input-group-text {
        background: #f1f5f9;
        color: var(--text-muted);
        border-radius: 0 8px 8px 0;
    }

    .btn-ocean {
        background: linear-gradient(135deg, #0d6efd, #00c6ff);
        border: none;
        border-radius: 30px;
        font-weight: 600;
        color: #fff;
    }

    .btn-ocean:hover {
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.5);
    }

</style>

<div class="auth-page container-fluid">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">

            <a href="/"><img class="my-4 d-block mx-auto"
                 style="width: 180px;"
                 src="{{ asset('images/FameOceans.png') }}"
                 alt="FameOceans Logo"></a>

            <div class="card glass-card">
                <div class="card-header">
                    Sign up For FameOceans
                </div>

                <div class="card-body">

                    @error('socialLoginInError')
                        <div class="alert alert-danger small">{{ $message }}</div>
                    @enderror

                    @if (session('status'))
                        <div class="alert alert-success small">{{ session('status') }}</div>
                    @endif

                    <!-- Google signup -->
                     <a href="/auth/google"
                       class="btn btn-light btn-google w-100">
                        <i class="fab fa-google me-2"></i> Continue with Google
                    </a>

                    <div class="text-center text-muted my-2">Or</div>

                    <!-- Registration Form -->
                    <form method="post" action="/register">
                        @csrf

                        <!-- Name -->
                        <div class="input-group mb-3">
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Full name"
                                   required>
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        @error('name')
                            <div class="text-warning small mb-2">{{ $message }}</div>
                        @enderror

                        <!-- Email -->
                        <div class="input-group mb-3">
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="Email address"
                                   required>
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                            <div class="text-warning small mb-2">{{ $message }}</div>
                        @enderror

                        <!-- Password -->
                        <div class="input-group mb-3" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password">
                            <span class="input-group-text" @click="show = !show" style="cursor:pointer">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                        </div>
                        @error('password')
                            <div class="text-warning small mb-2">{{ $message }}</div>
                        @enderror

                        <!-- Confirm Password -->
                        <div class="input-group mb-3" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password_confirmation"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   placeholder="Confirm password">
                            <span class="input-group-text" @click="show = !show" style="cursor:pointer">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <div class="text-warning small mb-2">{{ $message }}</div>
                        @enderror

                        <hr>

                        <button type="submit" class="btn btn-ocean w-100 py-2">Register</button>

                        <div class="text-center mt-3">
                            Already have an account? <a href="/login">Login</a>
                        </div>
                    </form>

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