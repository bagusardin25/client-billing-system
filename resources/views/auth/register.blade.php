<x-guest-layout>
    <div class="card auth-card p-4 p-md-5">
        <div class="text-center mb-5">
            <div class="mb-4">
                 <!-- Icon Circle -->
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 64px; height: 64px;">
                   <i class="bi bi-person-plus-fill fs-2"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-1">Create Account</h4>
            <p class="text-muted small">Start managing your billing efficiently</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                <label for="name" class="text-secondary">Full Name</label>
                @error('name')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="form-floating mb-3">
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                <label for="email" class="text-secondary">Email Address</label>
                @error('email')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-floating mb-3">
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                <label for="password" class="text-secondary">Password</label>
                @error('password')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Confirm Password">
                <label for="password_confirmation" class="text-secondary">Confirm Password</label>
                @error('password_confirmation')
                    <div class="text-danger small mt-1 ps-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit -->
            <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary shadow-sm text-uppercase text-xs fw-bold tracking-wide">
                    Create Account
                </button>
            </div>

            <!-- Login Link -->
            <div class="text-center">
                <span class="text-muted small">Already valid?</span>
                <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-dark ms-1 small">
                    Sign In instead
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
