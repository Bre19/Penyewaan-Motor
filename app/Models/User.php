<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'current_address',
        'passport_number',
        'origin_country',
        'has_license',
        'role',
        'status',
        'trusted_rider_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_license' => 'boolean',
            'trusted_rider_at' => 'datetime',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(UserDocument::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function evaluatedSafetyScores(): HasMany
    {
        return $this->hasMany(RentalSafetyScore::class, 'evaluated_by');
    }

    public function checkedRentalChecklists(): HasMany
    {
        return $this->hasMany(RentalChecklist::class, 'checked_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function hasTrustedRiderBadge(): bool
    {
        return $this->trusted_rider_at !== null;
    }
}