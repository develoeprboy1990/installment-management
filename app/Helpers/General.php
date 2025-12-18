<?php
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getUserSetting')) {
    
    function getUserSetting(string $key, bool $returnUrl = false)
    {
        
        $user_setting =  Setting::where('user_id', Auth::id())
            ->where('key', $key)
            ->value('value');
        
        // For image settings, return the full URL if requested or if it's a known image key
        $imageKeys = ['favicon', 'profile_image'];
        if ($user_setting && in_array($key, $imageKeys)) {
            // Use Storage::url() which respects the filesystem configuration
            return Storage::url($user_setting);
        }
        
        return $user_setting ? $user_setting : null;
    }
}
