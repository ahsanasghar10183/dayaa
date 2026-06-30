<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SumUpReader extends Model
{
    use SoftDeletes;

    protected $table = 'sumup_readers';

    protected $fillable = [
        'organization_id',
        'sumup_reader_id',
        'name',
        'pairing_code',
        'status',
        'device_model',
        'device_serial_number',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function isPaired(): bool
    {
        return $this->status === 'paired';
    }
}
