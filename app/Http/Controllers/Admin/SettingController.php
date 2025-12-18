<?php

namespace App\Http\Controllers\Admin;
use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class SettingController extends Controller
{
    private $view_path = "backend.settings";
    function index(){

        $settings = Setting::where('user_id', Auth::id())
        ->pluck('value', 'key')
        ->toArray();
        return view($this->view_path.'/index', ['settings' => $settings]);

    }

    public function store(Request $request)
    {
        // Save simple key/value settings
        foreach ($request->input('settings', []) as $key => $value) {

            Setting::updateOrCreate(
                [
                    'key' => $key,
                    'user_id' => Auth::id(),
                ],
                [
                    'value' => $value,
                ]
            );
        }

        // Handle favicon upload (optional)
        if ($request->hasFile('favicon')) {
            $request->validate([
                'favicon' => 'nullable|image|mimes:png,ico,svg,gif,jpg,jpeg,webp|max:1024',
            ]);

            // Delete old favicon if exists
            $old = Setting::where('user_id', Auth::id())
                ->where('key', 'favicon')
                ->value('value');

            $path = $request->file('favicon')->store('settings', 'public');

            if (!empty($old) && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            Setting::updateOrCreate(
                [
                    'key' => 'favicon',
                    'user_id' => Auth::id(),
                ],
                [
                    'value' => $path,
                ]
            );
        }

        // Handle profile image upload (optional)
        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:2048',
            ]);

            // Delete old profile image if exists
            $old = Setting::where('user_id', Auth::id())
                ->where('key', 'profile_image')
                ->value('value');

            $path = $request->file('profile_image')->store('settings/profiles', 'public');

            if (!empty($old) && Storage::disk('public')->exists($old)) {
                Storage::disk('public')->delete($old);
            }

            Setting::updateOrCreate(
                [
                    'key' => 'profile_image',
                    'user_id' => Auth::id(),
                ],
                [
                    'value' => $path,
                ]
            );
        }


        return redirect()->route('admin.settings')->with('status', [
            'icon' => 'success',
            'message' => 'Settings saved successfully!'
        ]);


    }

    public function toggle(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'required|boolean',
        ]);

        Setting::updateOrCreate(
            [
                'key' => $request->key,
                'user_id' => auth()->id(),
            ],
            [
                'value' => $request->value ? '1' : '0',
            ]
        );

        return response()->json(['success' => true]);
    }

}
