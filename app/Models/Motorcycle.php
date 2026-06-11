<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motorcycle extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_UNAVAILABLE = 'unavailable';

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

    public static function statusLabels(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_UNAVAILABLE => 'Unavailable',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? 'Unknown';
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
