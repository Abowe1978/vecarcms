<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Reset Password</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
        }

        .login-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }

        .login-background {
            flex: 1;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .login-background::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(255,255,255,0.03) 10px,
                rgba(255,255,255,0.03) 20px
            );
            animation: slide 20s linear infinite;
        }

        @keyframes slide {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .login-background::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,0,0,0.55), rgba(0,0,0,0.35));
        }

        .login-form-container {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
        }

        .auth-input {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.25rem;
            background: white;
            font-size: 0.875rem;
        }

        .auth-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #3490dc;
            border: none;
            border-radius: 0.25rem;
            color: white;
            font-weight: 500;
            cursor: pointer;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .auth-button:hover {
            background-color: #2779bd;
        }

        .auth-title {
            text-align: left;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            font-weight: 600;
            color: #2d3748;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            max-height: 64px;
            width: auto;
        }

        .brand-logo {
            position: absolute;
            bottom: 30px;
            left: 30px;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .brand-logo img {
            max-height: 40px;
            width: auto;
        }
        
        .brand-tagline {
            position: absolute;
            bottom: 15px;
            left: 30px;
            z-index: 10;
            color: rgba(255,255,255,0.9);
            font-size: 0.875rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .error-message {
            color: #e53e3e;
            font-size: 0.75rem;
            margin-top: -0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="auth-page">
    @php
        $loginBackgrounds = [
            'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1600&q=80',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1600&q=80',
        ];
        $loginBackground = $loginBackgrounds[array_rand($loginBackgrounds)];
    @endphp
    <div class="login-container">
        <div class="login-background" style="background-image: url('{{ $loginBackground }}');">
            <div class="brand-logo">
                <img src="{{ asset('content/themes/dwntheme/assets/images/logo.png') }}" alt="{{ settings('site_name', 'VeCarCMS') }}">
                <span>{{ settings('site_name', 'VeCarCMS') }}</span>
            </div>
            <div class="brand-tagline">Modern Laravel CMS</div>
        </div>
        
        <div class="login-form-container">
            <div class="login-form">
                <div class="auth-logo">
                    <img src="{{ asset('content/themes/dwntheme/assets/images/logo.png') }}" alt="{{ settings('site_name', 'VeCarCMS') }}">
                </div>
                <div class="auth-title">
                    Reset Password
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Email address" required autofocus autocomplete="username" />
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <input id="password" class="auth-input" type="password" name="password" placeholder="New Password" required autocomplete="new-password" />
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" placeholder="Confirm New Password" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="auth-button">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
