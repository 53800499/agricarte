<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'category_id' => ['required', 'exists:categories,id'],
                'is_active' => ['boolean'],
                'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'additional_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            ]);

            // Création du produit
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
                'is_active' => $validated['is_active'] ?? true,
                'user_id' => auth()->id()
            ]);

            // Gestion de l'image principale
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/products', $filename);
                $product->main_image = str_replace('public/', '', $path);
                $product->save();
            }

            // Gestion des images supplémentaires
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('public/products', $filename);
                    $product->images()->create([
                        'image_path' => str_replace('public/', '', $path)
                    ]);
                }
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Le produit a été créé avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du produit: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la création du produit.')
                ->withInput();
        }
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'category_id' => ['required', 'exists:categories,id'],
                'is_active' => ['boolean'],
                'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
                'additional_images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
            ]);

            // Mise à jour du produit
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'],
                'category_id' => $validated['category_id'],
                'is_active' => $validated['is_active'] ?? true
            ]);

            // Gestion de l'image principale
            if ($request->hasFile('main_image')) {
                // Suppression de l'ancienne image
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }

                $image = $request->file('main_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/products', $filename);
                $product->main_image = str_replace('public/', '', $path);
                $product->save();
            }

            // Gestion des images supplémentaires
            if ($request->hasFile('additional_images')) {
                foreach ($request->file('additional_images') as $image) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->storeAs('public/products', $filename);
                    $product->images()->create([
                        'image_path' => str_replace('public/', '', $path)
                    ]);
                }
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Le produit a été mis à jour avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du produit: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Une erreur est survenue lors de la mise à jour du produit.')
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Suppression des images
            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Le produit a été supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du produit: ' . $e->getMessage());

            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du produit.');
        }
    }
}