<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class SeoForm
{
    public static function get(): array
    {
        return [
            Section::make()
                ->schema([
                    Select::make('post_id')
                        ->relationship('post', 'title')
                        ->unique('seos', 'post_id', null, 'id')
                        ->createOptionForm(PostForm::get())
                        ->editOptionForm(PostForm::get())
                        ->required()
                        ->preload()
                        ->searchable()
                        ->default(request('post_id') ?? '')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('title')
                        ->required()
                        ->columnSpanFull()
                        ->characterLimit(255),
                    TagsInput::make('keywords')
                        ->columnSpanFull(),
                    Textarea::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->characterLimit(255),
                ]),
        ];
    }
}
