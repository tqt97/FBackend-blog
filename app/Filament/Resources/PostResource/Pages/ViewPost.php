<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Models\Post;
use Filament\Actions;
use Filament\Actions\Action;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;

    // public function getTitle(): string|Htmlable
    // {
    //     $record = $this->getRecord();

    //     return $record->title;
    // }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Action::make('sendNotification')
                ->label('Send Notification')
                ->requiresConfirmation()
                ->icon('heroicon-o-bell')->action(function (Post $record) {
                    // event(new BlogPublished($record));
                })
                ->disabled(function (Post $record) {
                    return $record->isNotPublished();
                }),
            Action::make('preview')
                ->label('Preview')
                ->requiresConfirmation()
                ->icon('heroicon-o-eye')->url(function (Post $record) {
                    return route('post.show', $record->slug);
                }, true)
                ->disabled(function (Post $record) {
                    return $record->isNotPublished();
                }),
        ];
    }
}
