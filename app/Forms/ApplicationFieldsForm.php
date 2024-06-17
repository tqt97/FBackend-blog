<?php

namespace App\Forms;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class ApplicationFieldsForm
{
    public static function get(): array
    {
        return [
            TextInput::make('site_name')
                ->label(__('admin/setting.site_name'))
                ->autofocus()
                ->columnSpanFull(),
            Textarea::make('site_description')
                ->label(__('admin/setting.site_description'))
                ->columnSpanFull(),
            TextInput::make('support_email')
                ->label(__('admin/setting.support_email'))
                ->prefixIcon('heroicon-o-envelope'),
            TextInput::make('support_phone')
                ->prefixIcon('heroicon-o-phone')
                ->label(__('admin/setting.support_phone')),
            ColorPicker::make('theme_color')
                ->label(__('admin/setting.theme_color'))
                ->prefixIcon('heroicon-o-swatch')
                ->formatStateUsing(fn (?string $state): string => $state ?? config('filament.theme.colors.primary'))
                ->helperText(__('admin/setting.theme_color_helper_text')),
        ];
    }
}
