<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function __construct(
        public Setting $setting
    ) {
    }

    public function get(): ?Setting
    {
        return Cache::remember('setting', config('setting.expiration_cache_config_time'), callback: function () {
            return $this->setting->first();
        });
    }
}
