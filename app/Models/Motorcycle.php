<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorcycle extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'type',
        'year',
        'plate_number',
        'price_per_day',
        'image',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day' => 'decimal:2',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}