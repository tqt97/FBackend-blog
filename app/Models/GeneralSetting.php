<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'theme_color',
        'support_email',
        'support_phone',
        'google_analytics_id',
        'posthog_html_snippet',
        'seo_title',
        'seo_keywords',
        'seo_metadata',
        'social_network',
        'email_settings',
        'email_from_name',
        'email_from_address',
        'more_configs',
    ];

    protected function casts(): array
    {
        return [
            'seo_metadata' => 'array',
            'email_settings' => 'array',
            'social_network' => 'array',
            'more_configs' => 'array',
        ];
    }
}
