<x-auth :title="'Confirm Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-shield-alt text-warning fa-3x"></i>
        </div>
        <h3 class="font-weight-bold">Secure Area</h3>
        <p class="text-muted small">
            For your security, please confirm your password before continuing to your account settings.
        </p>
    </div>

    <form method="POST" action="/user/confirm-password">
        @csrf

        <div class="form-group mb-4" x-data="{ show: false }">
            <label for="password" class="small font-weight-bold text-dark">Password</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                </div>
                <input :type="show ? 'text' : 'password'" 
                       name="password" 
                       id="password"
                       class="form-control border-left-0 border-right-0 @error('password') is-invalid @enderror"
                       placeholder="Enter your password"
                       required 
                       autocomplete="current-password"
                       autofocus
                       style="height: 45px;">
                <div class="input-group-append">
                    <button class="btn btn-white border border-left-0 text-muted" 
                            type="button" 
                            @click="show = !show"
                            style="border-radius: 0 8px 8px 0;">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <div class="text-danger small mt-1">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold py-2"
                style="border-radius: 8px; height: 48px;">
            Confirm Password <i class="fas fa-check-circle ml-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="{{ url()->previous() }}" class="text-decoration-none small text-muted font-weight-bold">
                <i class="fas fa-arrow-left mr-1 small"></i> Cancel and Go Back
            </a>
        </div>
    </form>
</x-auth>