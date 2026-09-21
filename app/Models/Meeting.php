<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meeting_date',
        'start_time',
        'end_time',
        'venue',
        'meeting_notes',
        'discussed_topics',
        'decisions_resolutions',
        'action_items',
        'attachments_json',
        'status',
        'created_by',
        'rejection_reason',
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'attachments_json' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'meeting_id');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class, 'meeting_id');
    }
}
