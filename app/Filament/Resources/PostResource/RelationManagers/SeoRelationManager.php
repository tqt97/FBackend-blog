<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class SeoRelationManager extends RelationManager
{
    protected static string $relationship = 'seo';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('post_id')
            ->columns([
                // Tables\Columns\TextColumn::make('post_id'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('keywords')->badge(),
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
