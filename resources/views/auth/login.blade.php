<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-2xl border border-green-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="redirect_to" value="{{ old('redirect_to', request('redirect_to', session('auth_redirect_to'))) }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="input-label">Email Address</label>
            <div class="input-wrapper">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="auth-input" placeholder="name@example.com">
                <i class="fas fa-envelope"></i>
            </div>
            @if ($errors->has('email'))
                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="input-label">Password</label>
            <div class="input-wrapper">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="auth-input" placeholder="••••••••">
                <i class="fas fa-lock"></i>
            </div>
            @if ($errors->has('password'))
                <p class="mt-1 text-xs text-red-600 font-semibold">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-8" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div style="display: flex; align-items: center;">
                <input id="remember_me" type="checkbox" name="remember" 
                    style="width: 18px; height: 18px; border-radius: 4px; border: 2px solid #d1d5db; cursor: pointer;">
                <label for="remember_me" style="margin-left: 8px; font-size: 14px; font-weight: 600; color: #4b5563; cursor: pointer;">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 14px; font-weight: 700; color: var(--maroon); text-decoration: none;">
                    Forgot password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary">
            <span>Log In To System</span>
            <i class="fas fa-arrow-right"></i>
        </button>

        <div class="footer-links" style="margin-top: 30px; text-align: center; justify-content: center;">
            <span style="color: #6b7280;">New here?</span>
            <a href="{{ route('register') }}" style="margin-left: 5px;">Create an account</a>
        </div>
    </form>
</x-guest-layout>
