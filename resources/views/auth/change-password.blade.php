<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-6 text-center">
                <div class="mx-auto w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-lock-open fa-2x"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Security Update Required</h2>
                <p class="mt-2 text-sm text-gray-600">
                    For your security, you must change your default password before accessing your dashboard.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update_required') }}">
                @csrf

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block font-bold text-xs text-gray-700 uppercase tracking-widest mb-2">New Password</label>
                    <input id="password" class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label for="password_confirmation" class="block font-bold text-xs text-gray-700 uppercase tracking-widest mb-2">Confirm New Password</label>
                    <input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-red-800 border border-transparent rounded-md font-black text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Update Password & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
