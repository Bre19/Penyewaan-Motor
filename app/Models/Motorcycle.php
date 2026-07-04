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
        return $this->availableStockCount() > 0;
    }

    public function isUnavailable(): bool
    {
        return $this->availableStockCount() === 0;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    /**
     * Relasi lama.
     * Tetap dipertahankan agar tidak merusak kode yang sudah ada.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Seluruh unit fisik motor.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(MotorcycleStock::class);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Total seluruh stock.
     */
    public function totalStock(): int
    {
        return $this->stocks()->count();
    }

    /**
     * Total stock yang siap disewa.
     */
    public function availableStockCount(): int
    {
        return $this->stocks()
            ->where('status', MotorcycleStock::STATUS_AVAILABLE)
            ->count();
    }

    /**
     * Query stock yang tersedia.
     */
    public function availableStocks()
    {
        return $this->stocks()
            ->where('status', MotorcycleStock::STATUS_AVAILABLE);
    }

    /**
     * Apakah ada salah satu unit yang sedang digunakan.
     */
    public function hasActiveBooking(): bool
    {
        return $this->stocks()
            ->whereHas('bookings', function ($query) {
                $query->whereIn('status', [
                    Booking::STATUS_APPROVED,
                    Booking::STATUS_WAITING_PAYMENT,
                    Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                    Booking::STATUS_PAYMENT_CONFIRMED,
                    Booking::STATUS_READY_TO_DELIVER,
                    Booking::STATUS_ONGOING,
                ]);
            })
            ->exists();
    }

    /**
     * Apakah master motor boleh dihapus.
     */
    public function canBeDeleted(): bool
    {
        return ! $this->hasActiveBooking();
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENT
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::deleting(function (Motorcycle $motorcycle) {

            if ($motorcycle->hasActiveBooking()) {
                throw new \Exception(
                    'Motor tidak dapat dihapus karena masih memiliki unit yang sedang digunakan.'
                );
            }

            $motorcycle->stocks()->delete();

        });
    }
}