<?php

namespace App\Filament\Resources;

use App\Filament\Forms\CommentForm;
use App\Filament\Resources\CommentResource\Pages;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Blog';

    public static function getNavigationLabel(): string
    {
        return __('comment');
    }

    public static function getModelLabel(): string
    {
        return __('comment');
    }

    public static function getNavigationBadge(): ?string
    {
        return Comment::count();
    }

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema(CommentForm::get());
    }

    protected static ?string $recordTitleAttribute = 'comment';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('author'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('post.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
                Tables\Columns\TextColumn::make('approved_at')
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}
