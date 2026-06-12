<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserDocument extends Model
{
    use HasFactory;

    public const TYPE_PASSPORT = 'passport';
    public const TYPE_VISA = 'visa';
    public const TYPE_LICENSE = 'license';
    public const TYPE_SIGNATURE = 'signature';

    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'uploaded_at',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_at' => 'datetime',
            'size' => 'integer',
        ];
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_PASSPORT => 'Paspor',
            self::TYPE_VISA => 'Visa',
            self::TYPE_LICENSE => 'SIM',
            self::TYPE_SIGNATURE => 'Tanda Tangan Digital',
        ];
    }

    public function typeLabel(): string
    {
        return self::typeLabels()[$this->type] ?? 'Dokumen';
    }

    public function publicUrl(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}