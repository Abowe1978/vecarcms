<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email Address</h2>
        </div>

        <div class="mb-4 text-sm text-gray-600 text-center">
            <p class="mb-3">
                Thank you for registering with RREC! We've sent a verification link to your email address.
            </p>
            <p class="mb-3">
                <strong>Before you can access your member account, please verify your email address</strong> by clicking on the link we just emailed to you.
            </p>
            <p class="text-gray-500">
                If you didn't receive the email, check your spam folder or click the button below to resend it.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            A new verification link has been sent to your email address!
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 space-y-4">
            <!-- Resend Button -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-button type="submit" class="w-full justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    {{ __('Resend Verification Email') }}
                </x-button>
            </form>

            <!-- Additional Actions -->
            <div class="flex items-center justify-center space-x-4 pt-4 border-t border-gray-200">
                <a
                    href="{{ route('profile.show') }}"
                    class="text-sm text-gray-600 hover:text-gray-900 underline"
                >
                    Edit Profile
                </a>
                
                <span class="text-gray-300">|</span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 underline">
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Help Text -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-xs text-blue-800">
                <strong>Need help?</strong> If you're having trouble verifying your email, please contact our support team at 
                <a href="mailto:support@rrec.org.uk" class="font-medium underline">support@rrec.org.uk</a>
            </p>
        </div>
    </x-authentication-card>
</x-guest-layout>
