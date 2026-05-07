<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Capture which attributes changed before saving
        $changes = array_keys($user->getDirty());

        $user->save();

        if (!empty($changes)) {
            $fieldsChanged = implode(', ', array_map('ucfirst', $changes));
            $msg = "Security Alert: Your Mwigito Excel profile was recently updated. Changes made to: {$fieldsChanged}. If this wasn't you, contact support immediately.";
            
            // Send SMS
            if ($user->phone_number) {
                app(\App\Services\CelcomSmsService::class)->send($user->phone_number, $msg);
            }
            
            // Send Email
            if ($user->email) {
                try {
                    \Illuminate\Support\Facades\Mail::raw($msg, function ($mail) use ($user) {
                        $mail->to($user->email)->subject('Mwigito Excel: Profile Update Alert');
                    });
                } catch (\Exception $e) {
                    Log::error("Failed to send profile update email to {$user->email}: " . $e->getMessage());
                }
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
