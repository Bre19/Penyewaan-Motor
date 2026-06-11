<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalSafetyScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'evaluated_by',
        'no_violation_report',
        'negligent_damage',
        'reckless_report',
        'score',
        'badge_awarded',
        'notes',
        'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'no_violation_report' => 'boolean',
            'negligent_damage' => 'boolean',
            'reckless_report' => 'boolean',
            'score' => 'integer',
            'badge_awarded' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public static function calculateScore(
        bool $noViolationReport,
        bool $negligentDamage,
        bool $recklessReport
    ): int {
        $score = 100;

        if (! $noViolationReport) {
            $score -= 20;
        }

        if ($negligentDamage) {
            $score -= 30;
        }

        if ($recklessReport) {
            $score -= 40;
        }

        return max(0, $score);
    }

    public static function isTrustedRiderScore(int $score): bool
    {
        return $score >= 80;
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}