<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

class CommentForm
{
    public static function get(): array
    {
        return [
            Section::make()->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('post_id')
                    ->relationship('post', 'title')
                    ->required(),
                Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('approved')
                    ->required(),
            ]),
        ];
    }
}
