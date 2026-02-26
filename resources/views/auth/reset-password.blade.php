<x-auth :title="'Reset Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-key text-warning fa-3x"></i>
        </div>
        <h3 style="font-weight: 700;">Reset Password</h3>
        <p class="text-muted small">
            Please choose a strong new password for your account.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form action="/reset-password" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label small font-weight-bold">Email Address</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                <input type="email" name="email" id="email" 
                       value="{{ old('email', request()->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus>
            </div>
            @error('email')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="mb-3" x-data="{ show: false }">
            <label for="password" class="form-label small font-weight-bold">New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                <input :type="show ? 'text' : 'password'" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required>
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="mb-4" x-data="{ show: false }">
            <label for="password_confirmation" class="form-label small font-weight-bold">Confirm New Password</label>
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-check-double text-muted"></i></span>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                       class="form-control" placeholder="Repeat new password" required>
                <button class="btn btn-outline-secondary border-start-0" type="button" @click="show = !show">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 shadow-sm">
            Update Password <i class="fas fa-save ms-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="/login" class="text-decoration-none small text-muted">
                Cancel and Go Back
            </a>
        </div>
    </form>
</x-auth>