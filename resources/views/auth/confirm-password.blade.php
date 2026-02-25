@extends('layouts.auth')

@section('title', 'Confirm Password | FameOceans')

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
                    Confirm Your Password
                </div>

                <div class="card-body">

                    <p class="mb-3 text-center text-muted">
                        Please confirm your password before continuing.
                    </p>

                    <!-- Form -->
                    <form action="/user/confirm-password" method="POST">
                        @csrf

                        <!-- Password -->
                        <div class="input-group mb-4" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Password"
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

                        <div class="text-center mb-3">
                            <button type="submit" class="btn btn-ocean w-50 py-2">
                                Confirm
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