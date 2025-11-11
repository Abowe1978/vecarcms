<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.75rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-logo img {
            max-height: 64px;
            width: auto;
        }

        .auth-title {
            text-align: left;
            margin-bottom: 2rem;
            font-size: 0.875rem;
            color: #4a5568;
        }

        .auth-link {
            color: #3490dc;
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
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
                @if (session('status'))
                    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="auth-title">
                    SIGN IN BELOW:
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @php
                        $demoMode = config('app.demo_mode');
                        $demoEmail = 'admin@example.com';
                        $demoPassword = 'password';
                    @endphp

                    <input id="email" class="auth-input" type="email" name="email" placeholder="Email address" value="{{ old('email', $demoMode ? $demoEmail : '') }}" required autofocus autocomplete="username" />
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <input id="password" class="auth-input" type="password" name="password" placeholder="Password" value="{{ $demoMode ? $demoPassword : '' }}" required autocomplete="current-password" />
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($demoMode)
                        <p style="margin-top: 0.75rem; color: #6c757d; font-size: 0.875rem;">
                            Demo attiva: usa <strong>{{ $demoEmail }}</strong> / <strong>{{ $demoPassword }}</strong>
                        </p>
                    @endif

                    <div class="remember-forgot">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="auth-link" href="{{ route('password.request') }}">
                                Forgot your password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="auth-button">
                        LOGIN
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
