<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MotorcycleStock extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_BOOKED = 'booked';
    public const STATUS_RENTED = 'rented';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'motorcycle_id',
        'stock_code',
        'plate_number',
        'image',
        'status',
        'latitude',
        'longitude',
        'last_gps_update_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'last_gps_update_at' => 'datetime',
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
            self::STATUS_BOOKED => 'Booked',
            self::STATUS_RENTED => 'Sedang Disewa',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_INACTIVE => 'Inactive',
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

    public function canBeDeleted(): bool
    {
        return ! $this->hasActiveBooking();
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    /**
     * Master katalog motor.
     */
    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    /**
     * Booking yang menggunakan unit ini.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'motorcycle_stock_id');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Apakah unit sedang dipakai.
     */
    public function hasActiveBooking(): bool
    {
        return $this->bookings()
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_WAITING_PAYMENT,
                Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                Booking::STATUS_PAYMENT_CONFIRMED,
                Booking::STATUS_READY_TO_DELIVER,
                Booking::STATUS_ONGOING,
            ])
            ->exists();
    }

    /**
     * Sinkronkan status unit berdasarkan booking aktif.
     */
    public function syncStatus(): void
    {
        if (in_array($this->status, [
            self::STATUS_MAINTENANCE,
            self::STATUS_INACTIVE,
        ], true)) {
            return;
        }

        $activeBooking = $this->bookings()
            ->whereIn('status', [
                Booking::STATUS_APPROVED,
                Booking::STATUS_WAITING_PAYMENT,
                Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                Booking::STATUS_PAYMENT_CONFIRMED,
                Booking::STATUS_READY_TO_DELIVER,
                Booking::STATUS_ONGOING,
            ])
            ->latest()
            ->first();

        if (! $activeBooking) {

            $this->update([
                'status' => self::STATUS_AVAILABLE,
            ]);

            return;
        }

        $status = match ($activeBooking->status) {

            Booking::STATUS_APPROVED,
            Booking::STATUS_WAITING_PAYMENT,
            Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
            Booking::STATUS_PAYMENT_CONFIRMED,
            Booking::STATUS_READY_TO_DELIVER
                => self::STATUS_BOOKED,

            Booking::STATUS_ONGOING
                => self::STATUS_RENTED,

            default
                => self::STATUS_AVAILABLE,
        };

        if ($this->status !== $status) {
            $this->update([
                'status' => $status,
            ]);
        }
    }

    /**
     * GPS Dummy Bali.
     */
    public function generateDummyLocation(): void
    {
        $this->update([
            'latitude' => mt_rand(-89000000, -80000000) / 10000000,
            'longitude' => mt_rand(1145000000, 1158000000) / 10000000,
            'last_gps_update_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENT
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (MotorcycleStock $stock) {

            if (! $stock->latitude) {
                $stock->latitude = mt_rand(-89000000, -80000000) / 10000000;
            }

            if (! $stock->longitude) {
                $stock->longitude = mt_rand(1145000000, 1158000000) / 10000000;
            }

            if (! $stock->last_gps_update_at) {
                $stock->last_gps_update_at = now();
            }

        });

        static::deleting(function (MotorcycleStock $stock) {

            if ($stock->hasActiveBooking()) {
                throw new \Exception(
                    'Unit motor sedang digunakan sehingga tidak dapat dihapus.'
                );
            }

        });
    }
}