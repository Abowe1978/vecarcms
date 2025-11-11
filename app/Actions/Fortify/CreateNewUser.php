<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Services\GeocodingService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected GeocodingService $geocodingService;

    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            // Personal Information
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_phone' => ['nullable', 'string', 'max:20'],
            
            // Location (postcode required for section assignment)
            'postcode' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'size:2'],
            
            // Password
            'password' => $this->passwordRules(),
            
            // GDPR & Privacy (gdpr_consent is REQUIRED!)
            'gdpr_consent' => ['required', 'accepted'],
            'can_email' => ['nullable', 'boolean'],
            'can_sms' => ['nullable', 'boolean'],
            'can_post' => ['nullable', 'boolean'],
            'can_telephone' => ['nullable', 'boolean'],
            'is_hidden' => ['nullable', 'boolean'],
            
            // Terms
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Create user with all fields
        $user = User::create([
            // Personal Information
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            
            // Location
            'postcode' => $input['postcode'],
            'address' => $input['address'] ?? null,
            'city' => $input['city'] ?? null,
            'country' => $input['country'] ?? 'GB',
            
            // Contact
            'mobile_phone' => $input['mobile_phone'] ?? null,
            
            // GDPR & Privacy
            'gdpr_consent' => true, // Already validated as 'accepted'
            'can_email' => isset($input['can_email']) && $input['can_email'] ? true : false,
            'can_sms' => isset($input['can_sms']) && $input['can_sms'] ? true : false,
            'can_post' => isset($input['can_post']) && $input['can_post'] ? true : false,
            'can_telephone' => isset($input['can_telephone']) && $input['can_telephone'] ? true : false,
            'is_hidden' => isset($input['is_hidden']) && $input['is_hidden'] ? true : false,
        ]);

        // Assign 'user' role (VeCarCMS default role)
        $user->assignRole('user');

        Log::info('New user registered - awaiting email verification', [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name . ' ' . $user->surname,
            'postcode' => $user->postcode,
        ]);

        // NOTE: Section assignment and free plan will be done AFTER email verification
        // via the Verified event listener to ensure complete registration flow
        // User will be logged out after registration to force them to verify email first

        return $user;
    }

    /**
     * Auto-assign user to nearest section based on postcode
     */
    private function autoAssignSection(User $user, string $postcode): void
    {
        try {
            $nearestSection = $this->geocodingService->findNearestSection($postcode);
            
            if ($nearestSection) {
                $user->sections()->syncWithoutDetaching([
                    $nearestSection->id => ['joined_at' => now()]
                ]);
                
                $user->update(['primary_section_id' => $nearestSection->id]);
                
                Log::info('Section auto-assigned during registration', [
                    'user_id' => $user->id,
                    'section_id' => $nearestSection->id,
                    'section_name' => $nearestSection->name,
                    'postcode' => $postcode,
                    'distance' => $nearestSection->distance ?? null,
                ]);
            } else {
                Log::warning('No section found for postcode during registration', [
                    'user_id' => $user->id,
                    'postcode' => $postcode,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error auto-assigning section during registration', [
                'user_id' => $user->id,
                'postcode' => $postcode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't block registration if section assignment fails
        }
    }

    /**
     * Assign free membership plan if available (optional)
     */
    private function assignFreePlan(User $user): void
    {
        try {
            // Look for a free plan (amount = 0)
            $freePlan = Plan::where('amount', 0)
                           ->where('active', 1)
                           ->where('name', 'LIKE', '%Free%')
                           ->first();
            
            if ($freePlan) {
                UserPlan::create([
                    'user_id' => $user->id,
                    'plan_id' => $freePlan->id,
                    'start_date' => now(),
                    'end_date' => null, // Lifetime/no expiry
                    'status' => 'active',
                ]);
                
                Log::info('Free plan assigned during registration', [
                    'user_id' => $user->id,
                    'plan_id' => $freePlan->id,
                    'plan_name' => $freePlan->name,
                ]);
            } else {
                Log::info('No free plan available for auto-assignment', [
                    'user_id' => $user->id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error assigning free plan during registration', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            // Don't block registration if plan assignment fails
        }
    }
}
