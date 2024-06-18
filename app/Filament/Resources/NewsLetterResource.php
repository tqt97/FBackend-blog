<?php

namespace App\Filament\Resources;

use App\Filament\Forms\NewsLetterForm;
use App\Filament\Resources\NewsLetterResource\Pages;
use App\Models\NewsLetter;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsLetterResource extends Resource
{
    protected static ?string $model = NewsLetter::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Blog';

    public static function getNavigationLabel(): string
    {
        return __('news_letter');
    }

    public static function getModelLabel(): string
    {
        return __('news_letter');
    }

    public static function getNavigationBadge(): ?string
    {
        return NewsLetter::count();
    }

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema(NewsLetterForm::get());
    }

    protected static ?string $recordTitleAttribute = 'email';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('subscribed')
                    ->boolean(),
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
            'index' => Pages\ListNewsLetters::route('/'),
            'create' => Pages\CreateNewsLetter::route('/create'),
            'edit' => Pages\EditNewsLetter::route('/{record}/edit'),
        ];
    }
}
