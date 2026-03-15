<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'driver_license_number',
        'insurance_policy_starts_at',
        'insurance_policy_expires_at',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_two_factor_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_two_factor_expires_at' => 'datetime',
        'email_two_factor_sent_at' => 'datetime',
        'insurance_policy_starts_at' => 'date',
        'insurance_policy_expires_at' => 'date',
    ];


    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }


    public function getCarsCountAttribute(): int
    {
        return $this->cars()->count();
    }
}
