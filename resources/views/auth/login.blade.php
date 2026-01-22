<x-guest-layout>
    <div class="card auth-card p-4 p-md-5">
        <div class="text-center mb-5">
            <div class="mb-4">
                 <!-- Icon Circle -->
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px;">
                   <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-1">Welcome Back</h4>
            <p class="text-muted small">Please enter your details to sign in</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 alert alert-info rounded-3 small border-0" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                <label for="email" class="text-secondary">Email Address</label>
                @error('email')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                <label for="password" class="text-secondary">Password</label>
                @error('password')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label text-secondary small" for="remember_me">
                        Keep me logged in
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small fw-semibold text-primary" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <!-- Submit -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary shadow-sm text-uppercase text-xs fw-bold tracking-wide">
                    Sign In
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <span class="text-muted small">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-decoration-none fw-bold text-dark ms-1 small">
                    Create account
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
