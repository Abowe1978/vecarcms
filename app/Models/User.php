<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Traits\HasHiddenRecords;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PragmaRX\Google2FA\Google2FA;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasHiddenRecords;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'profile_image',
        'is_hidden',
        'last_login_at',
        'primary_section_id',
        'website',
        'communication_preferences',
        'address',
        'postcode',
        'city',
        'country',
        'mobile_phone',
        'stripe_customer_id',
        'mailchimp_subscriber_id',
        'mailchimp_status',
        'mailchimp_synced_at',
        'gdpr_consent'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_hidden' => 'boolean',
        'last_login_at' => 'datetime',
        'mailchimp_synced_at' => 'datetime',
        'gdpr_consent' => 'boolean'
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function groupLeaderOf()
    {
        return $this->hasMany(Group::class, 'group_leader_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'user_interests');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')->withTimestamps();
    }

    /**
     * The sections that the user belongs to.
     */
    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'section_user')
            ->withPivot('joined_at')
            ->withTimestamps()
            ->withCasts(['joined_at' => 'datetime']);
    }

    public function primarySection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'primary_section_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function allMessages()
    {
        return Message::forUser($this->id);
    }

    public function connections()
    {
        return $this->hasMany(Connection::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function currentPlan()
    {
        return $this->hasOne(UserPlan::class)->where('status', 'active')->latest();
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function gdprConsentHistory()
    {
        return $this->hasMany(GdprConsentHistory::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }

    public function vehicles()
    {
        return $this->hasMany(UserVehicle::class, 'user_id');
    }

    public function memberships()
    {
        return $this->hasMany(UserPlan::class);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    /**
     * Verifica il codice TOTP fornito per la 2FA
     */
    public function validateTwoFactorCode(string $code): bool
    {
        if (!$this->two_factor_secret) {
            return false;
        }
        $google2fa = new Google2FA();
        return $google2fa->verifyKey(decrypt($this->two_factor_secret), $code);
    }
    
    /**
     * Get nearby sections based on user's postcode
     *
     * @param float $radiusKm
     * @return \Illuminate\Support\Collection
     */
    public function getNearbySections(float $radiusKm = 50): \Illuminate\Support\Collection
    {
        if (empty($this->postcode)) {
            return collect();
        }
        
        $geocodingService = app(\App\Services\GeocodingService::class);
        return $geocodingService->getNearbySections($this->postcode, $radiusKm);
    }
    
    /**
     * Get the nearest section to the user
     *
     * @return Section|null
     */
    public function getNearestSection(): ?Section
    {
        if (empty($this->postcode)) {
            return null;
        }
        
        $geocodingService = app(\App\Services\GeocodingService::class);
        return $geocodingService->findNearestSection($this->postcode);
    }
}
