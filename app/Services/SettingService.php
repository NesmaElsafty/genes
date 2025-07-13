<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function getData()
    {
        return Setting::all();
    }

    public function getSettingById($id)
    {
        return Setting::find($id);
    }

    public function notificationIsValid($name)
    {
        $setting = Setting::where('name', $name)->first();
        return $setting->is_active;
    }



    public function toggleSetting($id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return null;
        }

        $setting->is_active = !$setting->is_active;
        $setting->save();
        return $setting;
    }
}
