<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getUserSetting')) {
    
    function getUserSetting(string $key)
    {
        
        $user_setting =  Setting::where('user_id', Auth::id())
            ->where('key', $key)
            ->value('value');
        return $user_setting ? $user_setting : null;
    }
}

if (!function_exists('getSettingAssetUrl')) {
    function getSettingAssetUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'backend/')) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset($path);
    }
}
