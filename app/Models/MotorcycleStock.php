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
        'notes',
    ];

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
     * Booking yang pernah menggunakan unit ini.
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
        if ($this->status === self::STATUS_MAINTENANCE) {
            return;
        }

        if ($this->status === self::STATUS_INACTIVE) {
            return;
        }

        if (! $this->hasActiveBooking()) {

            $this->update([
                'status' => self::STATUS_AVAILABLE,
            ]);

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

        $this->update([
            'status' => $status,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENT
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::deleting(function (MotorcycleStock $stock) {

            if ($stock->hasActiveBooking()) {
                throw new \Exception(
                    'Unit motor sedang digunakan sehingga tidak dapat dihapus.'
                );
            }

        });
    }
}