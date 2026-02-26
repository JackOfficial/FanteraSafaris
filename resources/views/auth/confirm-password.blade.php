<x-auth :title="'Confirm Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-shield-alt text-warning fa-3x"></i>
        </div>
        <h3 style="font-weight: 700;">Secure Area</h3>
        <p class="text-muted small">
            For your security, please confirm your password before continuing to your account settings.
        </p>
    </div>

    <form method="POST" action="/user/confirm-password">
        @csrf

        <div class="mb-4" x-data="{ show: false }">
            <label for="password" class="form-label small font-weight-bold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                <input :type="show ? 'text' : 'password'" 
                       name="password" 
                       id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Enter your password"
                       required 
                       autocomplete="current-password"
                       autofocus>
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
            Confirm Password <i class="fas fa-check-circle ms-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="{{ url()->previous() }}" class="text-decoration-none small text-muted">
                Cancel and Go Back
            </a>
        </div>
    </form>
</x-auth>