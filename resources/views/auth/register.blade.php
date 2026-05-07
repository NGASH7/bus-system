<x-guest-layout title="Join the Fleet" subtitle="Create your account to start managing journeys">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="input-label">Full Name</label>
            <div class="input-wrapper">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="auth-input" placeholder="Enter your full name">
                <i class="fas fa-user"></i>
            </div>
            @if ($errors->has('name'))
                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email" class="input-label">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="auth-input" placeholder="name@example.com">
                <i class="fas fa-envelope"></i>
            </div>
            @if ($errors->has('email'))
                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Phone Number -->
        <div class="form-group">
            <label for="phone_number" class="input-label">Phone Number</label>
            <div class="input-wrapper">
                <input id="phone_number" type="tel" name="phone_number" value="{{ old('phone_number') }}" required autocomplete="tel"
                    class="auth-input" placeholder="e.g. 0712345678">
                <i class="fas fa-phone"></i>
            </div>
            @if ($errors->has('phone_number'))
                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $errors->first('phone_number') }}</p>
            @endif
        </div>

        <!-- Password Grid -->
        <div class="grid-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label for="password" class="input-label">Password</label>
                <div class="input-wrapper">
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="auth-input" placeholder="Password">
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="input-label">Confirm</label>
                <div class="input-wrapper">
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="auth-input" placeholder="Confirm">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        @if ($errors->has('password'))
            <p class="mb-4 text-xs text-red-600 font-semibold">{{ $errors->first('password') }}</p>
        @endif

        <button type="submit" class="btn-primary" style="margin-top: 10px;">
            <span>Create Account</span>
            <i class="fas fa-user-plus"></i>
        </button>

        <div class="footer-links" style="margin-top: 30px; text-align: center; justify-content: center;">
            <span style="color: #6b7280;">Already member?</span>
            <a href="{{ route('login') }}" style="margin-left: 5px;">Login here</a>
        </div>
    </form>
</x-guest-layout>
