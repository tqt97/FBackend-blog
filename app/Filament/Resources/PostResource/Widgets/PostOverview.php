<?php

namespace App\Filament\Resources\PostResource\Widgets;

use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PostOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Published Post', Post::published()->count()),
            Stat::make('Scheduled Post', Post::scheduled()->count()),
            Stat::make('Pending Post', Post::pending()->count()),
        ];
    }
}
