<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FarmerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:farmer');
    }

    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->with(['category', 'images'])
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('farmer.products', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('farmer.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit' => 'required|string|max:50',
            'is_available' => 'boolean',
            'min_order' => 'nullable|integer|min:1',
            'delivery_radius' => 'nullable|integer|min:0',
            'harvest_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:harvest_date',
            'organic' => 'boolean',
            'allergens' => 'nullable|string',
            'storage_conditions' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
        ]);

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'unit' => $request->unit,
                'is_available' => $request->is_available ?? true,
                'min_order' => $request->min_order,
                'delivery_radius' => $request->delivery_radius,
                'harvest_date' => $request->harvest_date,
                'expiry_date' => $request->expiry_date,
                'organic' => $request->organic ?? false,
                'allergens' => $request->allergens,
                'storage_conditions' => $request->storage_conditions,
                'nutritional_info' => $request->nutritional_info,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_main' => false
                    ]);
                }
            }

            return redirect()->route('farmer.products.index')
                ->with('success', 'Produit créé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du produit: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('farmer.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit' => 'required|string|max:50',
            'is_available' => 'boolean',
            'min_order' => 'nullable|integer|min:1',
            'delivery_radius' => 'nullable|integer|min:0',
            'harvest_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:harvest_date',
            'organic' => 'boolean',
            'allergens' => 'nullable|string',
            'storage_conditions' => 'nullable|string',
            'nutritional_info' => 'nullable|string',
        ]);

        try {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'is_available' => $request->is_available ?? true,
                'min_order' => $request->min_order,
                'delivery_radius' => $request->delivery_radius,
                'harvest_date' => $request->harvest_date,
                'expiry_date' => $request->expiry_date,
                'organic' => $request->organic ?? false,
                'allergens' => $request->allergens,
                'storage_conditions' => $request->storage_conditions,
                'nutritional_info' => $request->nutritional_info,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create([
                        'image_path' => $path,
                        'is_main' => false
                    ]);
                }
            }

            return redirect()->route('farmer.products.index')
                ->with('success', 'Produit mis à jour avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du produit: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Supprimer les images associées
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $product->delete();

            return redirect()->route('farmer.products.index')
                ->with('success', 'Produit supprimé avec succès');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du produit: ' . $e->getMessage());
        }
    }
} 