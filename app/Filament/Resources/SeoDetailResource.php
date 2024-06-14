<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\PostStatus;
use App\Models\SeoDetail;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SeoDetailResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SeoDetailResource\RelationManagers;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class SeoDetailResource extends Resource
{
    protected static ?string $model = SeoDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Blog';

    public static function getNavigationBadge(): ?string
    {
        return SeoDetail::count();
    }

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('post_id')
                            ->relationship('post', 'title')
                            ->unique('seo_details', 'post_id', null, 'id')
                            ->createOptionForm(static::getPostForm())
                            ->editOptionForm(static::getPostForm())
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
                        Textarea::make('keywords')
                            ->columnSpanFull()
                            ->characterLimit(255),
                        Textarea::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->characterLimit(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeoDetails::route('/'),
            'create' => Pages\CreateSeoDetail::route('/create'),
            'edit' => Pages\EditSeoDetail::route('/{record}/edit'),
        ];
    }

    public static function getPostForm()
    {
        return [
            Forms\Components\Section::make('Blog Details')
                ->schema([
                    Forms\Components\Fieldset::make('Titles')
                        ->schema([
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

                            TextInput::make('title')
                                ->live(true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                    'slug',
                                    Str::slug($state)
                                ))
                                ->required()
                                ->unique('posts', 'title', null, 'id')
                                ->maxLength(255),

                            TextInput::make('slug')
                                ->maxLength(255),

                            Textarea::make('description')
                                ->maxLength(255)
                                ->columnSpanFull(),

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
                        ]),
                    Forms\Components\RichEditor::make('body')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Fieldset::make('Feature Image')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Cover Photo')
                                ->directory('/blog-feature-images')
                                ->hint('This cover image is used in your blog post as a feature image. Recommended image size 1200 X 628')
                                ->image()
                                ->preserveFilenames()
                                ->imageEditor()
                                ->maxSize(1024 * 5)
                                ->rules('dimensions:max_width=1920,max_height=1004')
                                ->required(),
                            TextInput::make('photo_alt_text')->required(),
                        ])->columns(1),

                    Forms\Components\Fieldset::make('Status')
                        ->schema([

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
                        ]),
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->nullable(false)
                        ->default(auth()->id()),

                ]),
        ];
    }
}
