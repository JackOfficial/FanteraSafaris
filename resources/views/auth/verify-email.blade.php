<x-auth :title="'Verify Email | Fantera Safaris'">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-envelope-open-text text-warning fa-4x"></i>
        </div>

        <h3 class="font-weight-bold mb-3">Verify Your Email</h3>
        
        <p class="text-muted small mb-4">
            Thanks for signing up! Before getting started, please verify your email address 
            by clicking the link we just sent to your inbox.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success border-0 shadow-sm small py-2 mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                A new verification link has been sent to your email.
            </div>
        @endif

        <div class="bg-light p-3 border rounded mb-4" style="border-radius: 10px;">
            <p class="small text-muted mb-0">
                <i class="fas fa-info-circle mr-1 text-primary"></i>
                Don't see it? Check your <strong>spam or junk folder</strong>.
            </p>
        </div>

        <form method="POST" action="/email/verification-notification">
            @csrf
            <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold py-2 mb-3" style="border-radius: 8px; height: 48px;">
                Resend Verification Email <i class="fas fa-paper-plane ml-2"></i>
            </button>
        </form>

        <div class="mt-4">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-link text-muted small font-weight-bold text-decoration-none p-0">
                    <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                </button>
            </form>
        </div>
    </div>
</x-auth>