<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoDetail extends Model
{
    use HasFactory;

    const KEYWORDS = [
        'technology',
        'innovation',
        'science',
        'artificial intelligence',
        'machine learning',
        'data science',
        'coding',
        'programming',
        'web development',
        'cybersecurity',
        'digital marketing',
        'social media',
        'business',
        'finance',
        'health',
        'fitness',
        'travel',
        'food',
        'photography',
        'music',
        'movies',
        'fashion',
        'sports',
        'gaming',
        'books',
        'education',
        'history',
        'culture',
    ];

    protected $fillable = [
        'post_id',
        'title',
        'keywords',
        'description',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'post_id' => 'integer',
            'user_id' => 'integer',
            'keywords' => 'json',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class)->orderByDesc('id');
    }
}
