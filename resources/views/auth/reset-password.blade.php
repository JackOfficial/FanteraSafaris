<x-auth :title="'Reset Password | Fantera Safaris'">
    <div class="text-center mb-4">
        <div class="mb-3">
            <i class="fas fa-key text-warning fa-3x"></i>
        </div>
        <h3 class="font-weight-bold">Reset Password</h3>
        <p class="text-muted small">
            Please choose a strong new password for your account.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small py-2 mb-4 border-0 shadow-sm text-center">
            {{ session('status') }}
        </div>
    @endif

    <form action="/reset-password" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div class="form-group mb-3">
            <label for="email" class="small font-weight-bold text-dark">Email Address</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                </div>
                <input type="email" name="email" id="email" 
                       value="{{ old('email', request()->email) }}"
                       class="form-control border-left-0 @error('email') is-invalid @enderror"
                       required autofocus
                       style="border-radius: 0 8px 8px 0; height: 45px;">
            </div>
            @error('email')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="form-group mb-3" x-data="{ show: false }">
            <label for="password" class="small font-weight-bold text-dark">New Password</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                </div>
                <input :type="show ? 'text' : 'password'" name="password" id="password"
                       class="form-control border-left-0 border-right-0 @error('password') is-invalid @enderror"
                       placeholder="Min. 8 characters" required
                       style="height: 45px;">
                <div class="input-group-append">
                    <button class="btn btn-white border border-left-0 text-muted" type="button" @click="show = !show"
                            style="border-radius: 0 8px 8px 0;">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        <div class="form-group mb-4" x-data="{ show: false }">
            <label for="password_confirmation" class="small font-weight-bold text-dark">Confirm New Password</label>
            <div class="input-group shadow-sm">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0">
                        <i class="fas fa-check-double text-muted"></i>
                    </span>
                </div>
                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                       class="form-control border-left-0 border-right-0" placeholder="Repeat new password" required
                       style="height: 45px;">
                <div class="input-group-append">
                    <button class="btn btn-white border border-left-0 text-muted" type="button" @click="show = !show"
                            style="border-radius: 0 8px 8px 0;">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold py-2"
                style="border-radius: 8px; height: 48px;">
            Update Password <i class="fas fa-save ml-2"></i>
        </button>

        <div class="mt-4 text-center">
            <a href="/login" class="text-decoration-none small text-muted font-weight-bold">
                <i class="fas fa-arrow-left mr-1 small"></i> Cancel and Go Back
            </a>
        </div>
    </form>
</x-auth>