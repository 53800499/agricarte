<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class UserSettingController extends Controller
{
    /**
     * Display the user's settings.
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $user->userSettings ?? new Setting();
        return view('settings.index', compact('user', 'settings'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->fill($validated);
        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Update the user's notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
            'marketing_emails' => ['boolean'],
        ]);

        $user = Auth::user();
        $settings = $user->userSettings ?? new Setting(['user_id' => $user->id]);
        $settings->notifications = $validated;
        $settings->save();

        return back()->with('success', 'Préférences de notification mises à jour avec succès.');
    }

    /**
     * Update the user's privacy settings.
     */
    public function updatePrivacy(Request $request)
    {
        $validated = $request->validate([
            'show_email' => ['boolean'],
            'show_phone' => ['boolean'],
            'show_address' => ['boolean'],
        ]);

        $user = Auth::user();
        $settings = $user->userSettings ?? new Setting(['user_id' => $user->id]);
        $settings->privacy = $validated;
        $settings->save();

        return back()->with('success', 'Paramètres de confidentialité mis à jour avec succès.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}
