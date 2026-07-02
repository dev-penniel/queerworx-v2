<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;


class Article extends Model
{

    use HasRoles;
    
    protected $fillable = [
        'title',
        'slug',
        'body',
        'exerpt',
        'views',
        'claps',
        'thumbnail',
        'img_credit',
        'status',
        'published_date',
    ];

    protected $casts = [
        'created_at' =>  'date',
        'published_at' => 'date',
        'published_date' => 'date',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('status', 'approved')->latest();
    }
}
