<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'checked_by',
        'helmet_available',
        'brakes_normal',
        'headlight_normal',
        'brake_light_normal',
        'turn_signals_normal',
        'tires_proper',
        'mirrors_complete',
        'stnk_available',
        'motorcycle_condition_photo',
        'customer_with_helmet_photo',
        'notes',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'helmet_available' => 'boolean',
            'brakes_normal' => 'boolean',
            'headlight_normal' => 'boolean',
            'brake_light_normal' => 'boolean',
            'turn_signals_normal' => 'boolean',
            'tires_proper' => 'boolean',
            'mirrors_complete' => 'boolean',
            'stnk_available' => 'boolean',
            'checked_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
}