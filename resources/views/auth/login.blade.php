@extends('layouts.auth')

@section('content')



<div class="auth-page container-fluid">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">

           <a href="/">
        <img class="my-4 d-block mx-auto"
                 style="width: 180px"
                 src="{{ asset('images/FameOceans.png') }}"
                 alt="FameOceans Logo">
        </a> 

            <div class="card glass-card">
                <div class="card-header">
                    Login to FameOceans
                </div>

                <div class="card-body">

                    @error('socialLoginInError')
                        <div class="alert alert-danger small">{{ $message }}</div>
                    @enderror

                    @if (session('status'))
                        <div class="alert alert-success small">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="/auth/google"
                       class="btn btn-light btn-google w-100">
                        <i class="fab fa-google me-2"></i> Continue with Google
                    </a>

                    <div class="text-center text-muted my-2">or</div>

                    <form method="POST" action="/login">
                        @csrf

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
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="input-group mb-3" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password">
                            <span class="input-group-text"
                                  style="cursor:pointer"
                                  @click="show = !show">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </span>
                        </div>

                        @error('password')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="/forgot-password">Forgot password?</a>

                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-ocean w-100">
                            Login
                        </button>
                    </form>

                    <div class="text-center mt-3 text-muted">
                        Don’t have an account?
                        <a href="/register">Register</a>
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