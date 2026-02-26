<x-auth title="Login | Fantera Safaris">
    <h3 class="text-center mb-1" style="font-weight: 700;">Login</h3>
    <p class="text-muted text-center mb-4">Welcome back to Fantera Safaris</p>

    <div class="mb-4">
        <a class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center" href="{{ url('auth/redirect/google') }}">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" class="me-2" alt="Google">
            Continue with Google
        </a>
        
        @error('socialLoginInError')
            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center mb-4">
        <hr class="flex-grow-1">
        <span class="mx-3 text-muted small text-uppercase">Or email</span>
        <hr class="flex-grow-1">
    </div>

    @if (session('status'))
        <div class="alert alert-success small mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Email address" required autofocus>
            </div>
            @error('email')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="mb-3" x-data="{ show: false }">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                <input :type="show ? 'text' : 'password'" name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Password" required>
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small" for="remember">Remember Me</label>
            </div>
            <a href="/forgot-password" class="small text-primary text-decoration-none">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm mb-3">
            Login <i class="fas fa-sign-in-alt ms-2"></i>
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="mb-0">Don't have an account? <a href="/register" class="text-primary font-weight-bold text-decoration-none">Register</a></p>
    </div>
</x-auth>