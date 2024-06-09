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
            BaseWidget\Stat::make('Published Post', Post::published()->count()),
            BaseWidget\Stat::make('Scheduled Post', Post::scheduled()->count()),
            BaseWidget\Stat::make('Pending Post', Post::pending()->count()),
        ];
    }
}
