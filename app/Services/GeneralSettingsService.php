<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\GeneralSetting;

class GeneralSettingsService
{
    public function __construct(
        public GeneralSetting $generalSetting
    ) {
    }

    public function get(): ?GeneralSetting
    {
        return Cache::remember('general_settings', config('general-settings.expiration_cache_config_time'), callback: function () {
            return $this->generalSetting->first();
        });
    }
}
