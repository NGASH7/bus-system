<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $redirectTo = $request->string('redirect_to')->toString();
        if ($this->isSafeInternalPath($redirectTo)) {
            $request->session()->put('url.intended', url($redirectTo));
            $request->session()->put('auth_redirect_to', $redirectTo);
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $redirectTo = (string) $request->input('redirect_to', '');
        if ($this->isSafeInternalPath($redirectTo)) {
            $request->session()->put('url.intended', url($redirectTo));
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Send Welcome SMS & Email
        $welcomeMessage = "Welcome to Mwigito Excel, {$user->name}! Your account has been successfully created. Book your next trip with us today.";

        if ($user->phone_number) {
            app(\App\Services\CelcomSmsService::class)->send($user->phone_number, $welcomeMessage);
        }

        if ($user->email) {
            try {
                \Illuminate\Support\Facades\Mail::raw($welcomeMessage, function ($mail) use ($user) {
                    $mail->to($user->email)->subject('Welcome to Mwigito Excel!');
                });
            } catch (\Exception $e) {
                Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function isSafeInternalPath(string $path): bool
    {
        if ($path === '' || !Str::startsWith($path, '/')) {
            return false;
        }

        return !Str::startsWith($path, ['//', '/\\']);
    }
}
