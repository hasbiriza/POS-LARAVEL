<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    public static function getSetting($key)
    {
        $setting = Setting::first();
        return $setting ? $setting->$key : null;
    }
}
