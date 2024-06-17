<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Filament\Support\Facades\FilamentColor;
use Symfony\Component\HttpFoundation\Response;

class GeneralSettingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = GeneralSetting::first();

        if ($settings?->theme_color) {
            FilamentColor::register([
                'primary' => $settings->theme_color,
            ]);
        }

        if ($settings?->site_name) {
            config([
                'app.name' => $settings->site_name,
            ]);
        }
        return $next($request);
    }
}
