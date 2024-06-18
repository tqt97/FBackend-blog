<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Section;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class TagForm
{
    public static function get(): array
    {
        return [
            Section::make()
                ->schema([
                    TextInput::make('name')
                        ->live(true)->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                            'slug',
                            Str::slug($state)
                        ))
                        ->unique('tags', 'name', null, 'id')
                        ->required()
                        ->characterLimit(50),
                    TextInput::make('slug')
                        ->unique('tags', 'slug', null, 'id')
                        ->readOnly()
                        ->characterLimit(50),
                ]),
        ];
    }
}
