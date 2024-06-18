<?php

namespace App\Filament\Forms;

use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\PostStatus;
use Illuminate\Support\Str;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DateTimePicker;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class PostForm
{
    public static function get(): array
    {
        return [
            Grid::make(3)
                ->schema([
                    Section::make('Main')
                        ->description('Main content of the post')
                        ->schema([
                            TextInput::make('title')
                                ->live(true)
                                // ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                //     'slug',
                                //     Str::slug($state)
                                // ))
                                ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                    // if (($get('slug') ?? '') !== Str::slug($old)) {
                                    //     return;
                                    // }

                                    if ($get('slug') === Str::slug($state)) {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                    $set('photo_alt_text', Str::slug($state));
                                })
                                ->required()
                                ->unique('posts', 'title', null, 'id')
                                ->characterLimit(255),
                            TextInput::make('slug')
                                ->unique('posts', 'slug', null, 'id')
                                ->readOnly()
                                ->characterLimit(255),
                            TextInput::make('description')
                                ->characterLimit(255),
                            Select::make('user_id')
                                ->relationship('user', 'name')
                                ->nullable(false)
                                ->default(auth()->id()),
                            RichEditor::make('body')
                                ->required()
                                ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->columnSpan(2),
                    Section::make('Attributes')
                        ->description('description')
                        ->schema([
                            FileUpload::make('image')
                                ->label('Cover Photo')
                                ->directory('/uploads/images/blog-feature-images')
                                ->hint('Recommended image size 1200 X 628')
                                ->image()
                                ->preserveFilenames()
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    null,
                                    '16:9',
                                    '4:3',
                                    '1:1',
                                ])
                                ->maxSize(1024 * 5)
                                ->rules('dimensions:max_width=1920,max_height=1004')
                                ->optimize('webp')
                                ->required(),
                            TextInput::make('photo_alt_text')->required(),
                            Select::make('tag_id')
                                ->multiple()
                                ->preload()
                                ->createOptionForm(TagForm::get())
                                ->searchable()
                                ->relationship('tags', 'name')
                                ->columnSpanFull(),
                            Select::make('category_id')
                                ->multiple()
                                ->preload()
                                ->createOptionForm(CategoryForm::get())
                                ->searchable()
                                ->relationship('categories', 'name')
                                ->columnSpanFull(),
                            ToggleButtons::make('status')
                                ->live()
                                ->inline()
                                ->options(PostStatus::class)
                                ->required(),
                            DateTimePicker::make('scheduled_for')
                                ->visible(function ($get) {
                                    return $get('status') === PostStatus::SCHEDULED->value;
                                })
                                ->required(function ($get) {
                                    return $get('status') === PostStatus::SCHEDULED->value;
                                })
                                ->minDate(now()->addMinutes(5))
                                ->native(false),
                        ])
                        ->collapsible()
                        ->columnSpan(1),
                ]),
        ];
    }
}
