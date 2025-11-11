<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Personal Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <x-label for="name" value="{{ __('First Name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="given-name" />
                    </div>

                    <!-- Surname -->
                    <div>
                        <x-label for="surname" value="{{ __('Surname') }}" />
                        <x-input id="surname" class="block mt-1 w-full" type="text" name="surname" :value="old('surname')" required autocomplete="family-name" />
                    </div>
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Mobile Phone -->
                <div class="mt-4">
                    <x-label for="mobile_phone" value="{{ __('Mobile Phone') }}" />
                    <x-input id="mobile_phone" class="block mt-1 w-full" type="tel" name="mobile_phone" :value="old('mobile_phone')" autocomplete="tel" placeholder="+44 7XXX XXXXXX" />
                    <p class="mt-1 text-xs text-gray-500">For important membership notifications</p>
                </div>
            </div>

            <!-- Location Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Location</h3>
                <p class="text-sm text-gray-600 mb-4">We'll use this to connect you with your nearest local section</p>
                
                <!-- Postcode -->
                <div>
                    <x-label for="postcode" value="{{ __('Postcode') }}" />
                    <x-input id="postcode" class="block mt-1 w-full" type="text" name="postcode" :value="old('postcode')" required autocomplete="postal-code" />
                    <p class="mt-1 text-xs text-gray-500">Required to find your nearest RREC section</p>
                </div>

                <!-- Address -->
                <div class="mt-4">
                    <x-label for="address" value="{{ __('Address') }}" />
                    <x-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" autocomplete="street-address" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <!-- City -->
                    <div>
                        <x-label for="city" value="{{ __('City') }}" />
                        <x-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" autocomplete="address-level2" />
                    </div>

                    <!-- Country -->
                    <div>
                        <x-label for="country" value="{{ __('Country') }}" />
                        <select id="country" name="country" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="GB" {{ old('country', 'GB') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="IE" {{ old('country') == 'IE' ? 'selected' : '' }}>Ireland</option>
                            <option value="FR" {{ old('country') == 'FR' ? 'selected' : '' }}>France</option>
                            <option value="DE" {{ old('country') == 'DE' ? 'selected' : '' }}>Germany</option>
                            <option value="IT" {{ old('country') == 'IT' ? 'selected' : '' }}>Italy</option>
                            <option value="ES" {{ old('country') == 'ES' ? 'selected' : '' }}>Spain</option>
                            <option value="NL" {{ old('country') == 'NL' ? 'selected' : '' }}>Netherlands</option>
                            <option value="BE" {{ old('country') == 'BE' ? 'selected' : '' }}>Belgium</option>
                            <option value="CH" {{ old('country') == 'CH' ? 'selected' : '' }}>Switzerland</option>
                            <option value="US" {{ old('country') == 'US' ? 'selected' : '' }}>United States</option>
                            <option value="CA" {{ old('country') == 'CA' ? 'selected' : '' }}>Canada</option>
                            <option value="AU" {{ old('country') == 'AU' ? 'selected' : '' }}>Australia</option>
                            <option value="NZ" {{ old('country') == 'NZ' ? 'selected' : '' }}>New Zealand</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Account Security -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Account Security</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div>
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                </div>
            </div>

            <!-- Privacy & Communication Preferences -->
            <div class="mb-6 p-4 border border-gray-300 rounded-lg bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Privacy & Communication Preferences</h3>
                
                <!-- GDPR Consent (REQUIRED) -->
                <div class="mb-4 p-3 bg-white border border-gray-300 rounded">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="gdpr_consent" id="gdpr_consent" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" required {{ old('gdpr_consent') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="gdpr_consent" class="font-medium text-gray-700">
                                I consent to RREC processing my personal data <span class="text-red-500">*</span>
                            </label>
                            <p class="text-gray-500 mt-1">
                                By checking this box, you agree to our processing of your personal information in accordance with our 
                                <a href="{{ route('policy.show') }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">Privacy Policy</a>. 
                                You can withdraw consent at any time.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Communication Preferences -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">How can we contact you?</label>
                    <p class="text-xs text-gray-500 mb-3">Select all that apply</p>
                    
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="can_email" id="can_email" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('can_email', true) ? 'checked' : '' }}>
                            <label for="can_email" class="ml-2 text-sm text-gray-700">Email</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="can_sms" id="can_sms" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('can_sms') ? 'checked' : '' }}>
                            <label for="can_sms" class="ml-2 text-sm text-gray-700">SMS / Text Messages</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="can_post" id="can_post" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('can_post') ? 'checked' : '' }}>
                            <label for="can_post" class="ml-2 text-sm text-gray-700">Post / Mail</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="can_telephone" id="can_telephone" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('can_telephone') ? 'checked' : '' }}>
                            <label for="can_telephone" class="ml-2 text-sm text-gray-700">Telephone</label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="mt-4">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_hidden" id="is_hidden" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded" {{ old('is_hidden') ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_hidden" class="font-medium text-gray-700">
                                Hide my profile from the public member directory
                            </label>
                            <p class="text-gray-500 mt-1">You can change this later in your profile settings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <x-checkbox name="terms" id="terms" required />
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="text-indigo-600 hover:text-indigo-500 underline">'.__('Terms of Service').'</a>',
                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="text-indigo-600 hover:text-indigo-500 underline">'.__('Privacy Policy').'</a>',
                                ]) !!}
                                <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            <div class="flex items-center justify-between mt-6">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Create Account') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
