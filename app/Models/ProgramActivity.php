<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramActivity extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'activity_date',
        'activity_time',
        'venue',
        'status',
        'featured_image_path',
        'image_path',
        'video_path',
        'pdf_path',
        'is_featured',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'activity_time' => 'datetime:H:i',
        'is_featured' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProgramActivityMedia::class)->orderBy('sort_order')->latest();
    }

    public function images(): HasMany
    {
        return $this->media()->where('type', 'image');
    }

    public function videos(): HasMany
    {
        return $this->media()->where('type', 'video');
    }
}
