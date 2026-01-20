<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Device extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'organization_id',
        'device_id',
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
        ];
    }

    /**
     * Boot the model and generate unique device ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($device) {
            if (empty($device->device_id)) {
                $device->device_id = 'DEV-' . strtoupper(Str::random(12));
            }
        });
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
