<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'payment_proof',
        'type',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that made the donation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who approved/rejected the donation.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope a query to only include pending donations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved donations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected donations.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    /**
     * Check if the donation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if the donation is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if the donation is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
