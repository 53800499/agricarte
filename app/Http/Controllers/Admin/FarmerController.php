<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'farmer');

        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Filtre par statut
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->where('is_active', $status);
        }

        // Tri
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'name':
                    $query->orderBy('name');
                    break;
            }
        }

        $farmers = $query->paginate(10);
        return view('admin.farmers.index', compact('farmers'));
    }

    public function store(Request $request)
    {
        try {
            Log::info('Donnée: ', $request->all());
            $request->merge([
                'is_active' => $request->has('is_active'),
            ]);
            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            // Créer l'agriculteur sans l'image pour le moment
            $farmer = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'role' => 'farmer',
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Traitement de l'image de profil
            if ($request->hasFile('profile_image')) {
                $image = $request->file('profile_image');

                // Log des détails de l'image
                Log::info('Profile image details:', [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize(),
                    'extension' => $image->getClientOriginalExtension()
                ]);

                // Vérifier si l'image est valide
                if (!$image->isValid()) {
                    Log::error('Profile image failed validation', [
                        'error' => $image->getErrorMessage(),
                        'file' => $image->getClientOriginalName()
                    ]);
                    return back()->withErrors(['profile_image' => 'L\'image n\'a pas pu être téléchargée. Veuillez vérifier le fichier.'])->withInput();
                }

                try {
                    // Enregistrer l'image
                    $path = $image->store('profile_images', 'public');
                    $farmer->profile_image = $path;
                    $farmer->save();
                    Log::info('Profile image uploaded successfully', ['path' => $path]);
                } catch (\Exception $e) {
                    Log::error('Error storing profile image', [
                        'error' => $e->getMessage(),
                        'file' => $image->getClientOriginalName()
                    ]);
                    return back()->withErrors(['profile_image' => 'Erreur lors de l\'enregistrement de l\'image.'])->withInput();
                }
            }

            return redirect()->route('admin.farmers.index')->with('success', 'Agriculteur ajouté avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating farmer', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création de l\'agriculteur.'])->withInput();
        }
    }

    public function show(User $farmer)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $farmer->id,
                'name' => $farmer->name,
                'email' => $farmer->email,
                'phone' => $farmer->phone,
                'address' => $farmer->address,
                'latitude' => $farmer->latitude,
                'longitude' => $farmer->longitude,
                'description' => $farmer->description,
                'is_active' => $farmer->is_active,
                'profile_image' => $farmer->profile_image ? asset('storage/' . $farmer->profile_image) : null
            ]);
        }

        return view('admin.farmers.show', compact('farmer'));
    }

    public function update(Request $request, User $farmer)
    {
        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $farmer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($farmer->profile_image) {
                Storage::disk('public')->delete($farmer->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $farmer->update($validated);

        return redirect()->route('admin.farmers.index')->with('success', 'Agriculteur mis à jour avec succès.');
    }

    public function destroy(User $farmer)
    {
        if ($farmer->profile_image) {
            Storage::disk('public')->delete($farmer->profile_image);
        }

        $farmer->delete();

        return redirect()->route('admin.farmers.index')->with('success', 'Agriculteur supprimé avec succès.');
    }

    public function create()
    {
        return view('admin.farmers.create');
    }
    public function edit(Request $request, $farmer)
    {
        $farmer = User::find($farmer);
        return view('admin.farmers.edit', compact('farmer'));
    }
}