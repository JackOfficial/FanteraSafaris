<x-auth :title="'Forgot Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-lock-open text-warning fa-3x"></i>
        </div>
        <h3 style="font-weight: 700;">Forgot Password?</h3>
        <p class="text-muted small">
            No worries! Enter your email below and we'll send you a secure link to reset your password.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-4">
            <i class="fas fa-paper-plane me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="/forgot-password">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label small font-weight-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="your@email.com"
                       required
                       autofocus>
            </div>
            @error('email')
                <div class="text-danger small mt-1">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
            Send Reset Link <i class="fas fa-arrow-right ms-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="/login" class="text-decoration-none small text-primary">
                <i class="fas fa-chevron-left me-1 small"></i> Back to Login
            </a>
        </div>
    </form>
</x-auth>