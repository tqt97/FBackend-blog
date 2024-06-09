<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Enums\PostStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\Pages\ManagePostTag;
use App\Filament\Resources\PostResource\Widgets\PostOverview;
use App\Filament\Resources\PostResource\Pages\ManagePostCategory;
use App\Filament\Resources\PostResource\Pages\ManagePostComments;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use App\Filament\Resources\PostResource\Pages\ManagePostSeoDetail;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    protected static ?string $activeNavigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'The number of posts.';

    public static function form(Form $form): Form
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->description(function (Post $record) {
                        return Str::limit($record->sub_title, 40);
                    })
                    ->searchable()->limit(20)->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function ($state) {
                        return $state->getColor();
                    })->alignCenter(),
                Tables\Columns\ImageColumn::make('cover_photo_path')->label('Image')->alignCenter(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()->alignCenter(),
                Tables\Columns\ImageColumn::make('cover_photo_path'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),

                // Tables\Filters\SelectFilter::make('status')
                //     ->options(PostStatus::class)
                //     ->searchable()
                //     ->multiple(),
            ])
            ->actions([
                // Tables\Actions\ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                // ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Post')
                ->schema([
                    Fieldset::make('General')
                        ->schema([
                            TextEntry::make('title'),
                            TextEntry::make('slug'),
                            TextEntry::make('sub_title'),
                        ]),
                    Fieldset::make('Publish Information')
                        ->schema([
                            TextEntry::make('status')
                                ->badge()->color(function ($state) {
                                    return $state->getColor();
                                }),
                            TextEntry::make('published_at')->visible(function (Post $record) {
                                return $record->status === PostStatus::PUBLISHED;
                            }),

                            TextEntry::make('scheduled_for')->visible(function (Post $record) {
                                return $record->status === PostStatus::SCHEDULED;
                            }),
                        ]),
                    Fieldset::make('Description')
                        ->schema([
                            TextEntry::make('body')
                                ->html()
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewPost::class,
            ManagePostSeoDetail::class,
            ManagePostComments::class,
            EditPost::class,
            ManagePostCategory::class,
            ManagePostTag::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PostOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'view' => Pages\ViewPost::route('/{record}'),
            'comments' => ManagePostComments::route('/{record}/comments'),
            'seoDetail' => ManagePostSeoDetail::route('/{record}/seo-details'),
            'categories' => ManagePostCategory::route('/{record}/categories'),
            'tags' => ManagePostTag::route('/{record}/tags'),
        ];
    }
}
