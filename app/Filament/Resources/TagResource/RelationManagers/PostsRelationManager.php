<?php

namespace App\Filament\Resources\TagResource\RelationManagers;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\PostStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Main')
                            ->description('Main content of the post')
                            ->schema([
                                TextInput::make('title')
                                    ->live(true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                        'slug',
                                        Str::slug($state)
                                    ))
                                    ->required()
                                    ->unique('posts', 'title', null, 'id')
                                    ->characterLimit(255),
                                TextInput::make('slug')
                                    ->unique('posts', 'slug', null, 'id')
                                    ->readOnly()
                                    ->characterLimit(255),
                                TextInput::make('sub_title')
                                    ->characterLimit(255),
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->nullable(false)
                                    ->default(auth()->id()),
                                Forms\Components\RichEditor::make('body')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->columnSpan(2),
                        Forms\Components\Section::make('Attributes')
                            ->description('description')
                            ->schema([
                                // Forms\Components\Fieldset::make('Feature Image')
                                //     ->schema([
                                Forms\Components\FileUpload::make('cover_photo_path')
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
                                Forms\Components\TextInput::make('photo_alt_text')->required(),
                                // ])

                                Forms\Components\TextInput::make('photo_alt_text')
                                    ->required(),
                                Forms\Components\Select::make('tag_id')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->live(true)->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                                'slug',
                                                Str::slug($state)
                                            ))
                                            ->unique('tags', 'name', null, 'id')
                                            ->required()
                                            ->maxLength(50),
                                        Forms\Components\TextInput::make('slug')
                                            ->unique('tags', 'slug', null, 'id')
                                            ->readOnly()
                                            ->maxLength(155),
                                    ])
                                    ->searchable()
                                    ->relationship('tags', 'name')
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('category_id')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->live(true)
                                            ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state) {

                                                $set('slug', Str::slug($state));
                                            })
                                            ->unique('categories', 'name', null, 'id')
                                            ->required()
                                            ->maxLength(155),
                                        Forms\Components\TextInput::make('slug')
                                            ->unique('categories', 'slug', null, 'id')
                                            ->readOnly()
                                            ->maxLength(255)
                                    ])
                                    ->searchable()
                                    ->relationship('categories', 'name')
                                    ->columnSpanFull(),
                                // Forms\Components\Fieldset::make('Status')
                                //     ->schema([
                                Forms\Components\ToggleButtons::make('status')
                                    ->live()
                                    ->inline()
                                    ->options(PostStatus::class)
                                    ->required(),
                                Forms\Components\DateTimePicker::make('scheduled_for')
                                    ->visible(function ($get) {
                                        return $get('status') === PostStatus::SCHEDULED->value;
                                    })
                                    ->required(function ($get) {
                                        return $get('status') === PostStatus::SCHEDULED->value;
                                    })
                                    ->minDate(now()->addMinutes(5))
                                    ->native(false),
                                // ]),

                            ])
                            ->collapsible()
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->limit(40)
                    ->description(function (Post $record) {
                        return Str::limit($record->sub_title);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function ($state) {
                        return $state->getColor();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
