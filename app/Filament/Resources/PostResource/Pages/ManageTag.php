<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Forms\TagForm;
use App\Filament\Resources\PostResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ManageTag extends ManageRelatedRecords
{
    protected static string $resource = PostResource::class;

    protected static string $relationship = 'tags';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getNavigationLabel(): string
    {
        return 'Tags';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(TagForm::get());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
