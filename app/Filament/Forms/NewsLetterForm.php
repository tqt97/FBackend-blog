<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class NewsLetterForm
{
    public static function get(): array
    {
        return [
            Section::make()->schema([
                TextInput::make('email')
                    ->email()
                    ->required(),
                Toggle::make('subscribed')
                    ->required(),
            ]),
        ];
    }
}
