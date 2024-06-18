<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Forms\CategoryForm;
use App\Filament\Resources\PostResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ManageCategory extends ManageRelatedRecords
{
    protected static string $resource = PostResource::class;

    protected static string $relationship = 'categories';

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function getNavigationLabel(): string
    {
        return 'Categories';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CategoryForm::get());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
