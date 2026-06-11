<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING_APPROVAL = 'pending_approval';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_WAITING_PAYMENT = 'waiting_payment';
    public const STATUS_WAITING_PAYMENT_VERIFICATION = 'waiting_payment_verification';
    public const STATUS_PAYMENT_CONFIRMED = 'payment_confirmed';
    public const STATUS_READY_TO_DELIVER = 'ready_to_deliver';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const TERMS_VERSION = 'v1.0';

    protected $fillable = [
        'user_id',
        'motorcycle_id',
        'booking_code',
        'start_date',
        'end_date',
        'duration_days',
        'delivery_location',
        'customer_note',
        'price_per_day',
        'total_price',
        'status',
        'terms_accepted_at',
        'terms_version',
        'terms_ip_address',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'price_per_day' => 'decimal:2',
            'total_price' => 'decimal:2',
            'terms_accepted_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (! $booking->booking_code) {
                do {
                    $code = 'BM-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                } while (static::where('booking_code', $code)->exists());

                $booking->booking_code = $code;
            }

            if (! $booking->status) {
                $booking->status = self::STATUS_PENDING_APPROVAL;
            }
        });
    }

    public static function blockingStatuses(): array
    {
        return [
            self::STATUS_PENDING_APPROVAL,
            self::STATUS_APPROVED,
            self::STATUS_WAITING_PAYMENT,
            self::STATUS_WAITING_PAYMENT_VERIFICATION,
            self::STATUS_PAYMENT_CONFIRMED,
            self::STATUS_READY_TO_DELIVER,
            self::STATUS_ONGOING,
        ];
    }

    public static function finalStatuses(): array
    {
        return [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED,
        ];
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING_APPROVAL => 'Menunggu Persetujuan',
            self::STATUS_APPROVED => 'Disetujui',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_WAITING_PAYMENT => 'Menunggu Pembayaran',
            self::STATUS_WAITING_PAYMENT_VERIFICATION => 'Menunggu Verifikasi Pembayaran',
            self::STATUS_PAYMENT_CONFIRMED => 'Pembayaran Dikonfirmasi',
            self::STATUS_READY_TO_DELIVER => 'Siap Diantar',
            self::STATUS_ONGOING => 'Sedang Berjalan',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? 'Tidak Diketahui';
    }

    public function canBeCancelledByCustomer(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING_APPROVAL,
            self::STATUS_APPROVED,
            self::STATUS_WAITING_PAYMENT,
        ], true);
    }

    public function canUploadPaymentProof(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_WAITING_PAYMENT,
        ], true);
    }

    public function canBeHandedOverByAdmin(): bool
    {
        return $this->status === self::STATUS_PAYMENT_CONFIRMED;
    }

    public function canBeCompletedByAdmin(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function hasAcceptedTerms(): bool
    {
        return $this->terms_accepted_at !== null;
    }

    public function recordStatusHistory(?string $from, string $to, ?int $changedBy = null, ?string $note = null): void
    {
        $this->statusHistories()->create([
            'changed_by' => $changedBy,
            'status_from' => $from,
            'status_to' => $to,
            'note' => $note,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motorcycle(): BelongsTo
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function rentalChecklist(): HasOne
    {
        return $this->hasOne(RentalChecklist::class);
    }

    public function rentalSafetyScore(): HasOne
    {
        return $this->hasOne(RentalSafetyScore::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class)->latest();
    }
}
