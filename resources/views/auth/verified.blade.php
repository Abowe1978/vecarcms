<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Email Verified Successfully!</h2>
        </div>

        <div class="mb-6 text-center">
            <p class="text-gray-600 mb-3">
                Your email address has been verified and your account is now active.
            </p>
            <p class="text-gray-900 font-medium mb-3">
                Next Step: Login and choose your membership plan to complete your registration.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('login') }}" class="block w-full">
                <x-button type="button" class="w-full justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Login to Your Account
                </x-button>
            </a>
        </div>

        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <p class="text-sm text-blue-800">
                <strong>What happens next?</strong>
            </p>
            <ul class="text-xs text-blue-700 mt-2 space-y-1 list-disc list-inside">
                <li>Login with your credentials</li>
                <li>Choose a membership plan that suits you</li>
                <li>Complete payment</li>
                <li>Access all member benefits!</li>
            </ul>
        </div>
    </x-authentication-card>
</x-guest-layout>

