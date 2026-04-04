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
                'favicon' => 'nullable|file|mimes:png,ico,svg,gif,jpg,jpeg,webp|max:1024',
            ]);

            // Delete old favicon if exists
            $old = Setting::where('user_id', Auth::id())
                ->where('key', 'favicon')
                ->value('value');

            if (!is_dir(public_path('backend/img/settings'))) {
                mkdir(public_path('backend/img/settings'), 0755, true);
            }

            $fileName = 'favicon_' . time() . '.' . $request->file('favicon')->extension();
            $request->file('favicon')->move(public_path('backend/img/settings'), $fileName);
            $path = 'backend/img/settings/' . $fileName;

            if (!empty($old)) {
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }

                $oldPublicPath = public_path($old);
                if (file_exists($oldPublicPath)) {
                    @unlink($oldPublicPath);
                }
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
