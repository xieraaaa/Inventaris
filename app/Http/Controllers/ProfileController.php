<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\{
    Auth,
    Log,
    Redirect,
    Storage
};

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
    public function update(ProfileUpdateRequest $request)
    {
        $newName = $request['name'];
        
        if (!is_null(User::firstWhere('name', $newName))) {
            // TODO Munculkan error jika nama yang baru sudah dipakai
            return response('Username sudah ada!', 409);
        }

        $request->user()['name'] = $newName;

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $user = $request->user();

        if ($request->hasFile('profile_photo')) {
            if (!is_null($user->profile_photo)) {
                Storage::disk('public')->delete('profile_pictures/' . $user->profile_photo);
            }
            
            $profile_photo = $request->file('profile_photo')->store('profile_pictures', 'public');
            $user->profile_photo = basename($profile_photo);
            $user->save();
        }

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

        return Redirect::to('/');
    }
}

