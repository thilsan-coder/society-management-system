<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'report_type',
        'title',
        'summary_json',
        'status',
        'submitted_by',
        'reviewed_by',
        'rejection_reason',
    ];

    protected $casts = [
        'summary_json' => 'array',
        'year' => 'integer',
        'month' => 'integer',
    ];

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
