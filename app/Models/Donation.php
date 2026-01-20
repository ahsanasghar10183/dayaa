<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Donation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'organization_id',
        'campaign_id',
        'device_id',
        'amount',
        'receipt_number',
        'donor_name',
        'donor_email',
        'donor_phone',
        'payment_method',
        'payment_status',
        'transaction_id',
        'sumup_transaction_id',
        'sumup_fee',
        'net_amount',
        'currency',
        'error_message',
        'error_code',
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'sumup_fee' => 'decimal:2',
            'net_amount' => 'decimal:2',
        ];
    }

    /**
     * Boot the model and generate unique receipt number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($donation) {
            if (empty($donation->receipt_number)) {
                $donation->receipt_number = 'RCP-' . date('Ymd') . '-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Check if donation payment was successful
     */
    public function isSuccessful(): bool
    {
        return $this->payment_status === 'success';
    }

    /**
     * Check if donation payment failed
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if donation payment is pending
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Get the organization that received this donation
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the campaign this donation belongs to
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the device used for this donation
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
