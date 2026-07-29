<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $fillable = [
        'title',
        'url',
        'thumbnail',
        'video_path',
        'position',
        'views',
        'clicks'
    ];
}
