<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'email_verified_at',
        'role',
        'limit_conversions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'integer',
        'limit_conversions' => 'integer',
    ];

    /**
     * Get all image conversions for the user.
     */
    public function imageConversions()
    {
        return $this->hasMany(ImageConversion::class);
    }

    /**
     * Get all donations for the user.
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        // Explicitly check for integer value 2, which represents admin role
        return (int)$this->role === 2;
    }

    /**
     * Check if the user is a premium user.
     */
    public function isPremium(): bool
    {
        return (int)$this->role === 1;
    }

    /**
     * Check if the user has reached their conversion limit.
     */
    public function hasReachedConversionLimit(): bool
    {
        // Premium users (role 1) and admins (role 2) have unlimited conversions
        if ($this->role >= 1) {
            return false;
        }

        // For regular users, check the conversion count against the limit
        return $this->imageConversions()->count() >= $this->limit_conversions;
    }

    /**
     * Get the number of conversions remaining for the user.
     */
    public function remainingConversions(): int
    {
        // Premium users and admins have unlimited conversions
        if ($this->role >= 1) {
            return PHP_INT_MAX; // Effectively unlimited
        }

        $used = $this->imageConversions()->count();
        $remaining = $this->limit_conversions - $used;
        
        return max(0, $remaining);
    }

    /**
     * Add conversion limit to the user.
     */
    public function addConversionLimit(int $amount = 50): void
    {
        // Always add conversions to the limit, even if the user is premium
        // This ensures that if they downgrade to regular, they'll have these conversions
        $this->limit_conversions += $amount;
        $this->save();
    }

    /**
     * Get the amount of conversions to add when a user donates for limit increase.
     */
    public function getLimitIncreaseAmount(): int
    {
        return 50; // Default 50 conversions per donation
    }
}   
