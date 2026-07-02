<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminDocument extends Model
{
    protected $fillable = [
        'title',
        'type',
        'description',
        'file_path',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];
}
