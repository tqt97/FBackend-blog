<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo',
        'favicon',
        'organization_name',
        'google_console_code',
        'google_analytic_code',
        'google_absence_code',
        'quick_links',
    ];


    protected function casts(): array
    {
        return [
            'quick_links' => 'json',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected function getLogoImageAttribute()
    {
        return asset('storage/' . $this->logo);
    }

    protected function getFavIconImageAttribute()
    {
        return asset('storage/' . $this->favicon);
    }
}
