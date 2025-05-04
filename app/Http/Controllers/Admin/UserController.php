<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'],
                'role' => ['required', 'string', 'in:user,farmer,admin'],
                'is_active' => ['boolean'],
                'address' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            ]);

            Log::info($validated);

            // Création de l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
                'address' => $validated['address'],
                
            ]);

            // Gestion de l'image de profil
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/profile_images', $filename);

                $user->profile_image = str_replace('public/', '', $path);
                $user->save();
            }

            // Si c'est un producteur, on ajoute les informations spécifiques
            if ($validated['role'] === 'farmer') {
                $user->farmer()->create([
                    'description' => $validated['description'],
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude']
                ]);
            }

            /* // Envoi d'un email de bienvenue
            try {
                Mail::to($user->email)->send(new WelcomeEmail($user));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de l\'email de bienvenue: ' . $e->getMessage());
            } */

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'L\'utilisateur a été créé avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la création de l\'utilisateur.')
                ->withInput();
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\-\+\s\(\)]{10,20}$/'],
            'role' => ['required', 'string', 'in:user,farmer,admin'],
            'is_active' => ['boolean'],
            'password' => ['nullable', 'confirmed', Password::min(8)],

            // Adresse
            'address' => ['nullable', 'string', 'max:255'],/*
            'password' => ['nullable', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'], */

            // Informations du producteur
            'description' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],

            // Photo de profil
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Mise à jour des informations de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role = $validated['role'];
        $user->is_active = $validated['is_active'] ?? true;

        // Mise à jour du mot de passe si fourni
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Mise à jour de l'adresse
        $user->address = $validated['address'];
        /* $user->city = $validated['city'];
        $user->postal_code = $validated['postal_code'];
        $user->country = $validated['country']; */

        // Mise à jour des informations du producteur
        if ($validated['role'] === 'farmer') {
            $user->description = $validated['description'];
            $user->latitude = $validated['latitude'];
            $user->longitude = $validated['longitude'];
        } else {
            // Si le rôle change et n'est plus farmer, on efface les informations spécifiques
            $user->description = null;
            $user->latitude = null;
            $user->longitude = null;
        }

        // Gestion de la photo de profil
        if ($request->hasFile('profile_image')) {
            // Suppression de l'ancienne image si elle existe
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Stockage de la nouvelle image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
}