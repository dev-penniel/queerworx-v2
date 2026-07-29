<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramActivityMedia extends Model
{
    protected $fillable = [
        'program_activity_id',
        'type',
        'file_path',
        'caption',
        'sort_order',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(ProgramActivity::class, 'program_activity_id');
    }
}
