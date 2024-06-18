<?php

namespace App\Filament\Resources;

use App\Filament\Forms\SeoForm;
use App\Filament\Resources\SeoResource\Pages;
use App\Models\Seo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoResource extends Resource
{
    protected static ?string $model = Seo::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationGroup = 'Blog';

    public static function getNavigationLabel(): string
    {
        return __('seo');
    }

    public static function getModelLabel(): string
    {
        return __('seo');
    }

    public static function getNavigationBadge(): ?string
    {
        return Seo::count();
    }

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema(SeoForm::get());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('post.title')->label('Post Title')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('SEO Title')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('SEO Description')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('keywords')
                    ->label('SEO Keywords')
                    ->badge(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSeos::route('/'),
            'create' => Pages\CreateSeo::route('/create'),
            'edit' => Pages\EditSeo::route('/{record}/edit'),
        ];
    }
}
