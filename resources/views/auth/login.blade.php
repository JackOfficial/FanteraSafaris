<x-auth title="Login | Fantera Safaris">
    <div class="text-center mb-4">
        <h3 class="font-weight-bold mb-1">Welcome Back</h3>
        <p class="text-muted small">Enter your details to access your safari adventures</p>
    </div>

    <div class="mb-4">
        <a class="btn btn-outline-dark btn-block py-2 d-flex align-items-center justify-content-center shadow-sm" 
           href="{{ url('auth/redirect/google') }}" 
           style="border-radius: 8px; transition: all 0.3s;">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" class="mr-2" alt="Google">
            <span class="font-weight-normal">Continue with Google</span>
        </a>
        
        @error('socialLoginInError')
            <div class="alert alert-danger mt-2 small p-2 text-center">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center mb-4">
        <div class="flex-grow-1 border-bottom"></div>
        <span class="mx-3 text-muted small text-uppercase font-weight-bold" style="letter-spacing: 1px;">Or email</span>
        <div class="flex-grow-1 border-bottom"></div>
    </div>

    @if (session('status'))
        <div class="alert alert-success small mb-4 border-0 shadow-sm text-center">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mb-4">
            <label class="small font-weight-bold text-dark">Email Address</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                </div>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="form-control border-left-0 @error('email') is-invalid @enderror" 
                       placeholder="name@example.com" required autofocus 
                       style="border-radius: 0 8px 8px 0; height: 45px;">
            </div>
            @error('email')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="form-group mb-4" x-data="{ show: false }">
            <div class="d-flex justify-content-between">
                <label class="small font-weight-bold text-dark">Password</label>
                <a href="/forgot-password" class="small text-primary font-weight-bold">Forgot?</a>
            </div>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                </div>
                <input :type="show ? 'text' : 'password'" name="password" 
                       class="form-control border-left-0 border-right-0 @error('password') is-invalid @enderror" 
                       placeholder="••••••••" required
                       style="height: 45px;">
                <div class="input-group-append">
                    <button class="btn btn-white border border-left-0 text-muted" 
                            type="button" @click="show = !show"
                            style="border-radius: 0 8px 8px 0;">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="custom-control custom-checkbox mb-4">
            <input type="checkbox" name="remember" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="custom-control-label small text-muted" for="remember">Keep me logged in</label>
        </div>

        <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold py-2" 
                style="border-radius: 8px; height: 48px; font-size: 1rem;">
            Sign In <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="text-muted small">New to Fantera Safaris? 
            <a href="/register" class="text-primary font-weight-bold">Create an account</a>
        </p>
    </div>
</x-auth>