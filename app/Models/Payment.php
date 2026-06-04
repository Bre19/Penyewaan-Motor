<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_WAITING_VERIFICATION = 'waiting_verification';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';

    public const METHOD_BANK_TRANSFER = 'bank_transfer';
    public const METHOD_E_WALLET = 'e_wallet';

    protected $fillable = [
        'booking_id',
        'user_id',
        'payment_code',
        'amount',
        'method',
        'payer_name',
        'proof_path',
        'note',
        'status',
        'uploaded_at',
        'verified_at',
        'rejected_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'uploaded_at' => 'datetime',
            'verified_at' => 'datetime',
            'rejected_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            if (! $payment->payment_code) {
                do {
                    $code = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
                } while (static::where('payment_code', $code)->exists());

                $payment->payment_code = $code;
            }

            if (! $payment->status) {
                $payment->status = self::STATUS_WAITING_VERIFICATION;
            }
        });
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_REJECTED => 'Ditolak',
        ];
    }

    public static function methodLabels(): array
    {
        return [
            self::METHOD_BANK_TRANSFER => 'Transfer Bank',
            self::METHOD_E_WALLET => 'E-Wallet',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? 'Tidak Diketahui';
    }

    public function methodLabel(): string
    {
        return self::methodLabels()[$this->method] ?? 'Tidak Diketahui';
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}