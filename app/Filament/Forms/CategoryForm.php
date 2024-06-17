<?php
namespace App\Filament\Forms;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Forms\Components\Section;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class CategoryForm
{
    public static function get(): array
    {
        return [
            Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->live(true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state) {
                                $set('slug', Str::slug($state));
                            })
                            ->unique('categories', 'name', null, 'id')
                            ->required()
                            ->characterLimit(255),
                        TextInput::make('slug')
                            ->unique('categories', 'slug', null, 'id')
                            ->readOnly()
                            ->characterLimit(255),
                    ]),
        ];
    }
}
