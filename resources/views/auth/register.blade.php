<x-auth :title="'Sign Up | Fantera Safaris'">
    <h3 class="text-center mb-1" style="font-weight: 700;">Create Account</h3>
    <p class="text-muted text-center mb-4">Join Fantera Safaris for your next adventure</p>

    <div class="mb-4">
        <a class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center" href="{{ url('auth/redirect/google') }}">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" class="me-2" alt="Google">
            Sign up with Google
        </a>
        @error('socialLoginInError')
            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center mb-4">
        <hr class="flex-grow-1">
        <span class="mx-3 text-muted small text-uppercase">Or register with email</span>
        <hr class="flex-grow-1">
    </div>

    @if (session('status'))
        <div class="alert alert-success small mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-user text-muted"></i></span>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="form-control @error('name') is-invalid @enderror" 
                       placeholder="Full Name" required autofocus />
            </div>
            @error('name')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="form-control @error('email') is-invalid @enderror" 
                       placeholder="Email Address" required />
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
                       placeholder="Password" required />
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="mb-4" x-data="{ show: false }">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-check-double text-muted"></i></span>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" 
                       class="form-control" 
                       placeholder="Confirm Password" required />
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm mb-3">
            Register <i class="fas fa-user-plus ms-2"></i>
        </button>

        <div class="text-center mt-3">
            <p class="mb-0">Already have an account? <a href="/login" class="text-primary font-weight-bold text-decoration-none">Login</a></p>
        </div>
    </form>
</x-auth>