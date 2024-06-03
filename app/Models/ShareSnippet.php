<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShareSnippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'script_code',
        'html_code',
    ];

    protected function casts(): array
    {
        return [
            'script_code' => 'string',
            'html_code' => 'string',
        ];
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('active', true);
    }
}
