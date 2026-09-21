<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'fine_type',
        'reason',
        'original_amount',
        'paid_amount',
        'remaining_amount',
        'doubling_count',
        'meeting_id',
        'fine_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'fine_date' => 'date',
        'original_amount' => 'float',
        'paid_amount' => 'float',
        'remaining_amount' => 'float',
        'doubling_count' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function paymentItems(): MorphMany
    {
        return $this->morphMany(PaymentItem::class, 'payable');
    }
}
