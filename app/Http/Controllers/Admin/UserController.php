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
                'is_active' => ['nullable'],
                'address' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            ]);

            // Création de l'utilisateur
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => $validated['role'],
                'is_active' => $request->has('is_active'),
                'address' => $validated['address'] ?? null,
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
                $user->create([
                    'description' => $validated['description'] ?? null,
                    'latitude' => $validated['latitude'] ?? null,
                    'longitude' => $validated['longitude'] ?? null
                ]);
            }

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'L\'utilisateur a été créé avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation: ' . json_encode($e->errors()));
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
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:user,farmer,admin'],
            'is_active' => ['nullable'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'address' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Mise à jour des informations de base
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->role = $validated['role'];
        $user->is_active = $request->has('is_active');
        $user->address = $validated['address'];

        // Mise à jour du mot de passe si fourni
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
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

        // Mise à jour des informations du producteur
            if ($validated['role'] === 'farmer') {
                    $user->update([
                        'description' => $validated['description'],
                        'latitude' => $validated['latitude'],
                        'longitude' => $validated['longitude']
                    ]);

            }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'L\'utilisateur a été mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès');
    }
}