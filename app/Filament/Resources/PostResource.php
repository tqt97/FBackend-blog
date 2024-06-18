<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Enums\PostStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Filament\Forms\PostForm;
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
use App\Filament\Resources\PostResource\Pages\ManageSeo;
use App\Filament\Resources\PostResource\Pages\ManageTag;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\Pages\ManageComment;
use App\Filament\Resources\PostResource\Pages\ManageCategory;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-minus';

    protected static ?string $activeNavigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Blog';

    public static function getNavigationLabel(): string
    {
        return __('post');
    }

    public static function getModelLabel(): string
    {
        return __('post');
    }

    // protected static ?string $recordTitleAttribute = 'title';
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'slug', 'author.name', 'category.name', 'tag.name'];
    }

    protected static ?int $navigationSort = 3;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'The number of posts.';

    public static function form(Form $form): Form
    {
        return $form->schema(PostForm::get());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')->label('Image')->alignCenter(),
                Tables\Columns\TextColumn::make('title')
                    ->description(function (Post $record) {
                        return Str::limit($record->description, 40);
                    })
                    ->searchable()->limit(20)->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function ($state) {
                        return $state->getColor();
                    })->alignCenter(),
                Tables\Columns\TextColumn::make('user.name')->label('Author')->alignCenter(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_for')
                    ->dateTime()
                    ->sortable(),

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
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
                            TextEntry::make('description'),
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
            EditPost::class,
            ManageSeo::class,
            ManageComment::class,
            ManageCategory::class,
            ManageTag::class,
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'view' => Pages\ViewPost::route('/{record}'),
            'comments' => ManageComment::route('/{record}/comments'),
            'seoDetail' => ManageSeo::route('/{record}/seo-detail'),
            'categories' => ManageCategory::route('/{record}/categories'),
            'tags' => ManageTag::route('/{record}/tags'),
        ];
    }
}
