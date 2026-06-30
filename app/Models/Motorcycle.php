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

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

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

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isUnavailable(): bool
    {
        return $this->status === self::STATUS_UNAVAILABLE;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Cek apakah motor sedang dipakai (booking aktif)
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', [
                \App\Models\Booking::STATUS_APPROVED,
                \App\Models\Booking::STATUS_WAITING_PAYMENT,
                \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                \App\Models\Booking::STATUS_PAYMENT_CONFIRMED,
                \App\Models\Booking::STATUS_READY_TO_DELIVER,
                \App\Models\Booking::STATUS_ONGOING,
            ])
            ->exists();
    }

    /**
     * Cek apakah aman untuk dihapus
     */
    public function canBeDeleted(): bool
    {
        return !$this->hasActiveBooking();
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENT (AUTO PROTECTION)
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::deleting(function ($motorcycle) {
            if ($motorcycle->hasActiveBooking()) {
                throw new \Exception('Motor tidak bisa dihapus karena sedang digunakan.');
            }
        });
    }
}