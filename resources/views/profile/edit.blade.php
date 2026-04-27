@php(
    $layout = Auth::user()->isAdmin()
        ? 'admin-layout'
        : (Auth::user()->isDriver() ? 'driver-layout' : 'app-layout')
)
<x-dynamic-component :component="$layout">

    <div class="{{ (Auth::user()->isAdmin() || Auth::user()->isDriver()) ? '' : 'py-12' }} profile-page-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 profile-page-shell">
            <h2 class="font-semibold text-2xl leading-tight profile-page-title">
                {{ __('Profile') }}
            </h2>

            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 profile-card shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        .profile-page-wrap {
            background: #f3f4f6;
        }

        .profile-page-shell {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        .profile-page-title {
            color: #800000;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .profile-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-top: 3px solid #800000;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.1);
        }

        .profile-card h2 {
            color: #800000;
            font-weight: 700;
        }

        .profile-card p {
            color: #4b5563;
        }

        .profile-card label {
            color: #1f2937;
            font-weight: 600;
        }

        .profile-card input[type="text"],
        .profile-card input[type="email"],
        .profile-card input[type="password"] {
            border-color: #d1d5db;
            border-radius: 10px;
            background: #fff;
        }

        .profile-card input:focus {
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.15);
        }

        .profile-card button {
            border-radius: 10px;
        }

        .profile-card .text-green-600,
        .profile-card .text-green-400 {
            color: #15803d !important;
            font-weight: 600;
        }

        .profile-card .text-red-600,
        .profile-card .text-red-400 {
            color: #b91c1c !important;
        }
    </style>
</x-dynamic-component>
