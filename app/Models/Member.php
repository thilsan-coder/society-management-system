<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_number',
        'join_date',
        'address',
        'emergency_contact',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function santhas(): HasMany
    {
        return $this->hasMany(Santha::class, 'member_id');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class, 'member_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'member_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'member_id');
    }

    // Dynamic calculations from DB records
    public function getTotalSanthaPaidAttribute(): float
    {
        return (float) $this->santhas()->sum('paid_amount');
    }

    public function getTotalSanthaOutstandingAttribute(): float
    {
        return (float) $this->santhas()->whereIn('status', ['unpaid', 'partially_paid'])->get()->sum(function ($s) {
            return $s->amount - $s->paid_amount;
        });
    }

    public function getTotalFinePaidAttribute(): float
    {
        return (float) $this->fines()->sum('paid_amount');
    }

    public function getTotalFineOutstandingAttribute(): float
    {
        return (float) $this->fines()->whereIn('status', ['unpaid', 'partially_paid'])->sum('remaining_amount');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('total_amount');
    }

    public function getTotalOutstandingAttribute(): float
    {
        return $this->total_santha_outstanding + $this->total_fine_outstanding;
    }
}
