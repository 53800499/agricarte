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
    public function index()
    {
        $categories = Category::all();
        $products = Product::with('farmer')->latest()->get();
        $farmers = User::where('role', 'farmer')->get();
        return view('admin.products.index', compact('products', 'farmers', 'categories'));
    }

public function store(Request $request)
{
    try {
        // Ajouter un log pour le début de la création d'un produit
        Log::info('Creating a new product', $request->all());

        // Merge pour les cases à cocher (booléens)
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
            'stock_quantity' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048', // Validation de l'image avec taille maximale
            'additional_images.*' => 'nullable|image|max:2048', // Validation des images supplémentaires
            'is_available' => 'nullable|boolean',
            'is_organic' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        Log::info('Validated data:', $validated);

        // Vérification et traitement de l'image principale
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Vérifier si l'image est valide
            if (!$image->isValid()) {
                Log::error('Image failed validation', ['image' => $image]);
                return response()->json([
                    'success' => false,
                    'message' => 'The image failed to upload. Please check the file.'
                ], 422);
            }

            // Enregistrer l'image principale
            $imagePath = $image->store('products', 'public');
            $validated['image'] = $imagePath;

            Log::info('Image uploaded successfully', ['image_path' => $imagePath]);
        }

        // Vérification et traitement des images supplémentaires
        if ($request->hasFile('additional_images')) {
            $additionalImages = $request->file('additional_images');

            // Traiter chaque image supplémentaire
            $additionalImagePaths = array_map(function ($file) {
                if ($file->isValid()) {
                    return $file->store('products/additional', 'public');
                } else {
                    Log::error('Additional image failed validation', ['file' => $file]);
                    return null;
                }
            }, $additionalImages);

            // Filtrer les images nulles (si une image n'a pas pu être téléchargée)
            $validated['additional_images'] = json_encode(array_filter($additionalImagePaths));
            Log::info('Additional images uploaded successfully', ['additional_images' => $validated['additional_images']]);
        }

        // Créer le produit dans la base de données
        $product = Product::create($validated);

        Log::info('Product created successfully', ['product_id' => $product->id]);

        // Retourner une réponse JSON avec succès
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed', ['errors' => $e->errors()]);
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        Log::error('Error creating product', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'An error occurred',
            'error' => $e->getMessage()
        ], 500);
    }
}





    public function show(Product $product)
    {
        return response()->json($product->load('farmer'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'farmer_id' => 'required|exists:users,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } elseif ($request->remove_image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = null;
        }

        $product->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['success' => true]);
    }
}