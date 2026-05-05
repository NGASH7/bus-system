<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mwigito Excel') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --maroon: #800000;
                --maroon-dark: #600000;
                --gold: #c9a84c;
                --gold-light: #e8c96d;
            }

            body {
                font-family: 'Inter', 'Outfit', sans-serif;
                margin: 0;
                background: #000;
            }

            .auth-bg {
                background: linear-gradient(rgba(128, 0, 0, 0.8), rgba(60, 0, 0, 0.9)), 
                            url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 40px 20px;
            }

            .auth-card {
                background: rgba(255, 255, 255, 0.98);
                width: 100%;
                max-width: 450px !important;
                border-radius: 32px;
                box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
                position: relative;
                overflow: hidden;
                padding: 50px 40px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .auth-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 6px;
                background: linear-gradient(90deg, var(--maroon), var(--gold), var(--maroon));
                background-size: 200% 100%;
                animation: gradientShift 4s linear infinite;
            }

            @keyframes gradientShift {
                0% { background-position: 0% 50%; }
                100% { background-position: 200% 50%; }
            }

            .logo-header {
                text-align: center;
                margin-bottom: 40px;
            }

            .logo-header img {
                height: 90px;
                width: auto;
                margin: 0 auto 20px;
                display: block;
                filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.1));
            }

            .logo-header h2 {
                font-family: 'Outfit', sans-serif;
                font-size: 28px;
                font-weight: 900;
                color: var(--maroon);
                text-transform: uppercase;
                letter-spacing: 1.5px;
                margin: 0 0 8px;
                line-height:1;
            }

            .logo-header p {
                color: #6b7280;
                font-size: 15px;
                font-weight: 500;
                margin: 0;
            }

            .input-label {
                display: block;
                font-size: 14px;
                font-weight: 700;
                color: #374151;
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .input-wrapper {
                position: relative;
                margin-bottom: 24px;
            }

            .input-wrapper i {
                position: absolute;
                left: 16px;
                top: 50%;
                transform: translateY(-50%);
                color: #9ca3af;
                font-size: 16px;
                transition: color 0.3s;
            }

            .auth-input {
                width: 100%;
                box-sizing: border-box;
                padding: 14px 16px 14px 48px !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 16px !important;
                font-size: 15px !important;
                font-family: 'Inter', sans-serif !important;
                transition: all 0.3s !important;
                background: #f9fafb !important;
                outline: none !important;
            }

            .auth-input:focus {
                border-color: var(--maroon) !important;
                background: white !important;
                box-shadow: 0 0 0 4px rgba(128, 0, 0, 0.1) !important;
            }

            .auth-input:focus + i {
                color: var(--maroon);
            }

            .btn-primary {
                width: 100%;
                background: var(--maroon);
                color: white !important;
                padding: 16px !important;
                border-radius: 16px !important;
                font-size: 16px !important;
                font-weight: 800 !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                border: none !important;
                cursor: pointer !important;
                transition: all 0.3s !important;
                box-shadow: 0 10px 20px rgba(128, 0, 0, 0.2) !important;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                text-decoration: none !important;
            }

            .btn-primary:hover {
                background: var(--maroon-dark) !important;
                transform: translateY(-2px) !important;
                box-shadow: 0 15px 30px rgba(128, 0, 0, 0.3) !important;
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .footer-links {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 24px;
                font-size: 14px;
            }

            .footer-links a {
                color: var(--maroon);
                text-decoration: none;
                font-weight: 600;
                transition: color 0.2s;
            }

            .footer-links a:hover {
                color: var(--maroon-dark);
                text-decoration: underline;
            }

            .fade-up {
                animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class="auth-bg">
            <div class="auth-card fade-up">
                <div class="logo-header">
                    <a href="/">
                        <img src="{{ asset('Images/image.png') }}" alt="Mwigito Excel Bus System" title="Mwigito Excel Bus System">
                    </a>
                    <h2>{{ $title ?? 'WELCOME BACK' }}</h2>
                    <p>{{ $subtitle ?? 'Enter your credentials to access the system' }}</p>
                </div>

                {{ $slot }}
            </div>

            <div class="mt-8 text-white text-opacity-50 text-sm text-center font-medium">
                &copy; {{ date('Y') }} Mwigito Excel Bus Management System. <br>
                All rights reserved.
            </div>
        </div>
    </body>

            <style>
                .auth-form-container {
                    padding: 40px;
                }
            </style>
            
            <div class="mt-8 text-white text-opacity-60 text-sm text-center">
                &copy; {{ date('Y') }} Mwigito Excel Bus Management System. <br>
                All rights reserved.
            </div>
        </div>
    </body>
</html>
