<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShareSnippetResource\Pages;
use App\Filament\Resources\ShareSnippetResource\RelationManagers;
use App\Models\ShareSnippet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShareSnippetResource extends Resource
{
    protected static ?string $model = ShareSnippet::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Share›';

    public static function getNavigationLabel(): string
    {
        return __('admin/navigation.share_snippet');
    }

    public static function getModelLabel(): string
    {
        return __('admin/share_snippet.share_snippet');
    }

    public static function getNavigationBadge(): ?string
    {
        return ShareSnippet::count();
    }

    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool
    {
        return !(self::$model::all()->count() > 0);
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Textarea::make('script_code')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('html_code')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('active')
                        ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('active')
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
            'index' => Pages\ListShareSnippets::route('/'),
            'create' => Pages\CreateShareSnippet::route('/create'),
            'edit' => Pages\EditShareSnippet::route('/{record}/edit'),
        ];
    }
}
