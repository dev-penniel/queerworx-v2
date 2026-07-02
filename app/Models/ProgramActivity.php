<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramActivity extends Model
{
    protected $fillable = [
        'program_id',
        'title',
        'description',
        'activity_date',
        'image_path',
        'video_path',
        'pdf_path',
        'is_featured',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
