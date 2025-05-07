<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductControllers extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('user', 'category');

        // Si l'utilisateur est un agriculteur, ne montrer que ses produits
        if (auth()->user()->role === 'farmer') {
            $query->where('user_id', auth()->id());
        }
        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filtre par statut
        if ($request->has('is_available')) {
            $query->where('is_available', $request->input('is_available'));
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

        $products = $query->paginate(10);
        $categories = Category::all();
        $farmers = User::where('role', 'farmer')->get();
        return view('admin.products.index', compact('products', 'farmers', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $farmers = User::where('role', 'farmer')->get();
        return view('admin.products.create', compact('categories', 'farmers'));
    }

    public function store(Request $request)
    {
        try {
            // Log des données reçues
            Log::info('Creating a new product', ['request' => $request->except('image', 'additional_images')]);

            // Vérification de la taille des images
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($image->getSize() > 10 * 1024 * 1024) { // 10MB
                    throw new \Exception('L\'image principale ne doit pas dépasser 10MB');
                }
            }

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    if ($image->getSize() > 10 * 1024 * 1024) { // 10MB
                        throw new \Exception('Les images supplémentaires ne doivent pas dépasser 10MB chacune');
                    }
                }
            }

            // Préparation des données booléennes
            $request->merge([
                'is_available' => $request->has('is_available'),
                'is_organic' => $request->has('is_organic'),
                'is_featured' => $request->has('is_featured'),
            ]);

            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:20',
                'category_id' => 'required|exists:categories,id',
                'user_id' => 'required|exists:users,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
                'is_available' => 'boolean',
                'is_organic' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            // Créer les dossiers s'ils n'existent pas
            if (!Storage::exists('public/products')) {
                Storage::makeDirectory('public/products');
            }
            if (!Storage::exists('public/products/additional')) {
                Storage::makeDirectory('public/products/additional');
            }

            // Traitement de l'image principale
            $fileNameToStore = 'noimage.jpg';
            if($request->hasFile('image')) {
                try {
                    $image = $request->file('image');
                    // 1:get files name with ext
                    $fileNameWithExt = $image->getClientOriginalName();
                    // 2:get just files name
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    // 3:get just files extension
                    $extension = $image->getClientOriginalExtension();
                    // 4 : file name to store
                    $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                    // 5 : uploader l'image
                    $path = $image->storeAs('public/products', $fileNameToStore);

                    Log::info('Main image uploaded successfully', [
                        'original_name' => $fileNameWithExt,
                        'stored_name' => $fileNameToStore,
                        'path' => $path,
                        'size' => $image->getSize()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error storing main image', [
                        'error' => $e->getMessage(),
                        'file' => $request->file('image')->getClientOriginalName()
                    ]);
                    throw $e;
                }
            }

            // Créer le produit
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'unit' => $validated['unit'],
                'category_id' => $validated['category_id'],
                'user_id' => $validated['user_id'],
                'is_available' => $validated['is_available'],
                'is_organic' => $validated['is_organic'],
                'is_featured' => $validated['is_featured'],
                'image' => $fileNameToStore
            ]);

            // Traitement des images supplémentaires
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $additionalImage) {
                    if ($additionalImage->isValid()) {
                        try {
                            // 1:get files name with ext
                            $fileNameWithExt = $additionalImage->getClientOriginalName();
                            // 2:get just files name
                            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                            // 3:get just files extension
                            $extension = $additionalImage->getClientOriginalExtension();
                            // 4 : file name to store
                            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                            // 5 : uploader l'image
                            $path = $additionalImage->storeAs('public/products/additional', $fileNameToStore);

                            $product->additionalImages()->create(['image_path' => $fileNameToStore]);
                            Log::info('Additional image uploaded successfully', [
                                'original_name' => $fileNameWithExt,
                                'stored_name' => $fileNameToStore,
                                'path' => $path,
                                'size' => $additionalImage->getSize()
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Error storing additional image', [
                                'error' => $e->getMessage(),
                                'file' => $additionalImage->getClientOriginalName()
                            ]);
                            continue;
                        }
                    }
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit ajouté avec succès.',
                    'product' => $product
                ]);
            }

            // Redirection basée sur le rôle de l'utilisateur
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.products.index')
                    ->with('success', 'Produit créé avec succès.');
            } else {
                return redirect()->route('farmer.products.index')
                    ->with('success', 'Produit créé avec succès.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating product', ['error' => $e->getMessage()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function show(Product $product)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock_quantity' => $product->stock_quantity,
                'unit' => $product->unit,
                'category_id' => $product->category_id,
                'category' => $product->category,
                'image' => $product->image ? Storage::url($product->image) : null,
                'is_available' => $product->is_available,
                'is_organic' => $product->is_organic,
                'is_featured' => $product->is_featured,
                'farmer' => $product->farmer,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at
            ]);
        }

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $farmers = User::where('role', 'farmer')->get();
        return view('admin.products.edit', compact('product', 'categories', 'farmers'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            // Log des données reçues
            Log::info('Updating product', ['product_id' => $product->id, 'request' => $request->except('image', 'additional_images')]);

            // Vérification de la taille des images
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if ($image->getSize() > 10 * 1024 * 1024) { // 10MB
                    throw new \Exception('L\'image principale ne doit pas dépasser 10MB');
                }
            }

            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    if ($image->getSize() > 10 * 1024 * 1024) { // 10MB
                        throw new \Exception('Les images supplémentaires ne doivent pas dépasser 10MB chacune');
                    }
                }
            }

            // Préparation des données booléennes
            $request->merge([
                'is_available' => $request->has('is_available'),
                'is_organic' => $request->has('is_organic'),
                'is_featured' => $request->has('is_featured'),
            ]);

            // Validation des données
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock_quantity' => 'required|numeric|min:0',
                'unit' => 'required|string|max:20',
                'category_id' => 'required|exists:categories,id',
                'user_id' => 'required|exists:users,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
                'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
                'is_available' => 'boolean',
                'is_organic' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            // Traitement de l'image principale
            if ($request->hasFile('image')) {
                try {
                    // Supprimer l'ancienne image si elle existe
                    if ($product->image && Storage::exists('public/' . $product->image)) {
                            Storage::delete('public/products/' . $product->image);
                    }

                    $image = $request->file('image');
                    $fileNameWithExt = $image->getClientOriginalName();
                    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    $extension = $image->getClientOriginalExtension();
                    $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                    $path = $image->storeAs('public/products', $fileNameToStore);
                    $validated['image'] = 'products/' . $fileNameToStore;

                    Log::info('Main image updated successfully', [
                        'product_id' => $product->id,
                        'original_name' => $fileNameWithExt,
                        'stored_name' => $fileNameToStore
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error updating main image', [
                        'product_id' => $product->id,
                        'error' => $e->getMessage()
                    ]);
                    throw $e;
                }
            }

            // Traitement des images supplémentaires
            if ($request->hasFile('additional_images')) {
                $additionalImages = [];
                foreach ($request->file('additional_images') as $image) {
                    try {
                        $fileNameWithExt = $image->getClientOriginalName();
                        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                        $extension = $image->getClientOriginalExtension();
                        $fileNameToStore = $fileName.'_'.time().'.'.$extension;
                        $path = $image->storeAs('public/products/additional', $fileNameToStore);
                        $additionalImages[] = 'products/additional/' . $fileNameToStore;

                        Log::info('Additional image uploaded successfully', [
                            'product_id' => $product->id,
                            'original_name' => $fileNameWithExt,
                            'stored_name' => $fileNameToStore
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Error storing additional image', [
                            'product_id' => $product->id,
                            'error' => $e->getMessage()
                        ]);
                        continue;
                    }
                }
                $validated['additional_images'] = json_encode($additionalImages);
            }
            if (!isset($validated['image'])) {
                $validated['image'] = $product->image;
            }


            // Mise à jour du produit
            $product->update($validated);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit mis à jour avec succès.',
                    'product' => $product
                ]);
            }

            if (auth()->check() && auth()->user()->role === 'admin') {
                return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour avec succès.');
            } else {
                return redirect()->route('farmer.products.index')->with('success', 'Produit mis à jour avec succès.');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating product', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Produit supprimé avec succès.');
    }
}
