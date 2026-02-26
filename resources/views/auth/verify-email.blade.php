<x-auth :title="'Verify Email | Fantera Safaris'">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-envelope-open-text text-warning fa-4x"></i>
        </div>

        <h3 class="mb-3" style="font-weight: 700;">Verify Your Email</h3>
        
        <p class="text-muted mb-4">
            Thanks for signing up! Before getting started, please verify your email address 
            by clicking the link we just sent to your inbox.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success small py-2 mb-4">
                <i class="fas fa-check-circle me-2"></i>
                A new verification link has been sent to your email.
            </div>
        @endif

        <div class="bg-light p-3 rounded mb-4">
            <p class="small text-muted mb-0">
                <i class="fas fa-info-circle me-1"></i>
                Don't see it? Check your <strong>spam or junk folder</strong>.
            </div>
        </div>

        <form method="POST" action="/email/verification-notification">
            @csrf
            <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm mb-3">
                Resend Verification Email
            </button>
        </form>

        <div class="mt-4">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-muted small text-decoration-none p-0">
                    <i class="fas fa-sign-out-alt me-1"></i> Log Out
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>