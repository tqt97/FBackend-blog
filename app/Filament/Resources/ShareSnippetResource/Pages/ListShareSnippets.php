<?php

namespace App\Filament\Resources\ShareSnippetResource\Pages;

use App\Filament\Resources\ShareSnippetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShareSnippets extends ListRecords
{
    protected static string $resource = ShareSnippetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
