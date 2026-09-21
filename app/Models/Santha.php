<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Santha extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'year',
        'month',
        'amount',
        'paid_amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'float',
        'paid_amount' => 'float',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function paymentItems(): MorphMany
    {
        return $this->morphMany(PaymentItem::class, 'payable');
    }

    public function getRemainingAttribute(): float
    {
        return max(0.0, (float) $this->amount - (float) $this->paid_amount);
    }
}
