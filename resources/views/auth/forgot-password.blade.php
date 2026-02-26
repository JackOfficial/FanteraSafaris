<x-auth :title="'Forgot Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-lock-open text-warning fa-3x"></i>
        </div>
        <h3 class="font-weight-bold">Forgot Password?</h3>
        <p class="text-muted small">
            No worries! Enter your email below and we'll send you a secure link to reset your password.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-4 border-0 shadow-sm text-center">
            <i class="fas fa-paper-plane mr-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="/forgot-password">
        @csrf

        <div class="form-group mb-4">
            <label for="email" class="small font-weight-bold text-dark">Email Address</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                </div>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="form-control border-left-0 @error('email') is-invalid @enderror"
                       placeholder="your@email.com"
                       required
                       autofocus
                       style="border-radius: 0 8px 8px 0; height: 45px;">
            </div>
            @error('email')
                <div class="text-danger small mt-1">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold py-2"
                style="border-radius: 8px; height: 48px;">
            Send Reset Link <i class="fas fa-arrow-right ml-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="/login" class="text-decoration-none small text-primary font-weight-bold">
                <i class="fas fa-chevron-left mr-1 small"></i> Back to Login
            </a>
        </div>
    </form>
</x-auth>