<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seo extends Model
{
    use HasFactory;

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
