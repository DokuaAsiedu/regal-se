<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_prefix',
        'phone',
        'phone_country_code',
        'status_id',
        'password',
        'role_id',
        'delivery_address',
        'delivery_address_landmark',
        'ghana_card_number',
        'date_of_birth',
        'company_name',
        'company_email',
        'company_phone_prefix',
        'company_phone',
        'company_phone_country_code',
        'company_address',
        'current_position',
        'employment_start_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole($code): bool
    {
        return $this->role?->code == $code;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function kycSubmissions()
    {
        return $this->hasMany(KYCSubmission::class);
    }

    public function approvedKyc()
    {
        return $this->kycSubmissions()
            ->approved()
            ->first();
    }
}
