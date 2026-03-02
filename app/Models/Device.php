<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Device extends Model
{
    use SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'organization_id',
        'device_id',
        'pairing_pin',
        'pairing_pin_expires_at',
        'is_paired',
        'api_token',
        'name',
        'location',
        'description',
        'status',
        'last_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_active' => 'datetime',
            'deleted_at' => 'datetime',
            'pairing_pin_expires_at' => 'datetime',
            'is_paired' => 'boolean',
        ];
    }

    /**
     * Boot the model and generate unique device ID and pairing PIN
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($device) {
            if (empty($device->device_id)) {
                $device->device_id = 'DEV-' . strtoupper(Str::random(12));
            }

            // Generate 6-digit pairing PIN
            $device->pairing_pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // PIN expires in 24 hours
            $device->pairing_pin_expires_at = now()->addHours(24);
            $device->is_paired = false;
        });
    }

    /**
     * Generate a new pairing PIN
     */
    public function regeneratePairingPin(): void
    {
        $this->update([
            'pairing_pin' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'pairing_pin_expires_at' => now()->addHours(24),
            'is_paired' => false,
        ]);
    }

    /**
     * Check if pairing PIN is valid (not expired)
     */
    public function isPairingPinValid(): bool
    {
        return $this->pairing_pin_expires_at && $this->pairing_pin_expires_at->isFuture();
    }

    /**
     * Mark device as paired
     */
    public function markAsPaired(): void
    {
        $this->update([
            'is_paired' => true,
            'pairing_pin' => null, // Clear PIN after successful pairing
            'pairing_pin_expires_at' => null,
        ]);
    }

    /**
     * Check if device is online
     */
    public function isOnline(): bool
    {
        return $this->status === 'online';
    }

    /**
     * Check if device is offline
     */
    public function isOffline(): bool
    {
        return $this->status === 'offline';
    }

    /**
     * Mark device as online
     */
    public function markOnline(): void
    {
        $this->update([
            'status' => 'online',
            'last_active' => now(),
        ]);
    }

    /**
     * Mark device as offline
     */
    public function markOffline(): void
    {
        $this->update(['status' => 'offline']);
    }

    /**
     * Get the organization that owns this device
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get all campaigns assigned to this device
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_device')
            ->withTimestamps();
    }

    /**
     * Get all donations made through this device
     */
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }
}
