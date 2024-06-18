<?php

namespace App\Filament\Forms\Settings;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class AnalyticsFieldsForm
{
    public static function get(): array
    {
        return [
            TextInput::make('google_analytics_id')
                ->label(__('google_analytics_id'))
                ->placeholder('UA-123456789-1'),
            Textarea::make('posthog_html_snippet')
                ->label(__('posthog_html_snippet'))
                ->placeholder('<script src=\'https://app.posthog.com/123456789.js\'></script>'),
        ];
    }
}
