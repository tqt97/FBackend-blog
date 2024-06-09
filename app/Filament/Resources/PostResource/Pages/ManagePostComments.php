<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Filament\Resources\PostResource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

class ManagePostComments extends ManageRelatedRecords
{
    protected static string $resource = PostResource::class;

    protected static string $relationship = 'comments';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    public function getTitle(): string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();

        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;

        return 'Manage Comments';
    }

    public function getBreadcrumb(): string
    {
        return 'Comments';
    }
    public static function getNavigationLabel(): string
    {
        return 'Comments';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Textarea::make('comment')
                    ->required()
                    ->characterLimit(65535)
                    ->columnSpanFull(),
                Toggle::make('approved'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('comment')
                    ->searchable(),
                ImageColumn::make('user')
                    ->label('Commented By'),
                Tables\Columns\ToggleColumn::make('approved')
                    ->beforeStateUpdated(function ($record, $state) {
                        if ($state) {
                            $record->approved_at = now();
                        } else {
                            $record->approved_at = null;
                        }

                        return $state;
                    }),
                Tables\Columns\TextColumn::make('approved_at')
                    ->placeholder('Not approved')
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
                // Tables\Filters\TrashedFilter::make()
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ForceDeleteAction::make(),
                // Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    // Tables\Actions\RestoreBulkAction::make(),
                    // Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            // ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
            //     SoftDeletingScope::class,
            // ]))
        ;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Comment')
                ->schema([
                    TextEntry::make('user.name')
                        ->label('Commented by'),
                    TextEntry::make('comment'),
                    TextEntry::make('created_at'),
                    TextEntry::make('approved_at')->label('Approved At')->placeholder('Not Approved'),

                ])
                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
        ]);
    }
}
