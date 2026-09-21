<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PosterAndMedia extends Model
{
    use HasFactory;

    protected $table = 'posters_and_media';

    protected $fillable = [
        'title',
        'media_type',
        'event_date',
        'event_time',
        'venue',
        'description',
        'file_path',
        'status',
        'submitted_by',
        'reviewed_by',
        'rejection_reason',
    ];

    protected $casts = [
        'event_date' => 'date',
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
