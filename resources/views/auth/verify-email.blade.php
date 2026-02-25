@extends('layouts.auth')

@section('title', 'Verify Email | FameOceans')

@section('content')
<div class="auth-page container-fluid">
    <div class="row w-100 justify-content-center">
        <div class="col-md-5 col-lg-4">

            <!-- Logo -->
            <div class="text-center my-4">
              <a href="/">  <img src="{{ asset('images/FameOceans.png') }}"
                     alt="FameOceans Logo"
                     style="width: 180px;"></a>
            </div>

            <!-- Glass Card -->
            <div class="card glass-card">
                <div class="card-header">
                    Email Verification Required
                </div>

                <div class="card-body text-center">

                    <!-- Success status -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success small">
                            A new verification link has been sent to your email address.
                        </div>
                    @endif

                    <!-- Main message -->
                    <p class="mb-3 text-muted">
                        Thanks for signing up! Before continuing, please verify your email address
                        by clicking the link we just sent to your inbox.
                    </p>

                    <p class="text-muted small mb-3">
                        If you don’t see the email, please check your spam or junk folder.
                    </p>

                    <!-- Resend form -->
                    <form method="POST" action="/email/verification-notification">
                        @csrf
                        <button type="submit" class="btn btn-ocean w-100 py-2">
                            Resend Verification Email
                        </button>
                    </form>

                    <div class="mt-3">
                        <a href="/login" class="fw-semibold">
                            Back to Login
                        </a>
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