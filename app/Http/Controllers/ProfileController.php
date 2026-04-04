<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle optional avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if (!is_dir(public_path('backend/img/avatars'))) {
                mkdir(public_path('backend/img/avatars'), 0755, true);
            }

            $fileName = 'avatar_' . time() . '.' . $file->extension();
            $file->move(public_path('backend/img/avatars'), $fileName);
            $path = 'backend/img/avatars/' . $fileName;

            // Delete old avatar if exists
            if (!empty($user->avatar)) {
                if (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $oldPublicPath = public_path($user->avatar);
                if (file_exists($oldPublicPath)) {
                    @unlink($oldPublicPath);
                }
            }

            $user->avatar = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }
}
