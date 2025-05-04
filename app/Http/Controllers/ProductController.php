<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'user']);

        // Si l'utilisateur est un agriculteur, ne montrer que ses produits
        /* if (auth()->user()->role === 'farmer') {
            $query->where('farmer_id', auth()->id());
        } */

        // Filtre par catégorie
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par prix
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        if ($request->has('sort')) {
            $sort = $request->sort;
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name');
                    break;
                case 'latest':
                    $query->latest();
                    break;
            }
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function indexs()
    {
        return Product::with('farmer:id,name')->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'farmer_name' => $product->farmer->name,
                'image' => $product->image,
            ];
        });
    }

    public function create()
    {
        // Vérifier si l'utilisateur est un agriculteur
        if (auth()->user()->role !== 'farmer') {
            abort(403, 'Accès non autorisé');
        }

        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est un agriculteur
        if (auth()->user()->role !== 'farmer') {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'category_id' => $validated['category_id'],
            'farmer_id' => auth()->id(),
            'is_active' => $validated['is_active'] ?? true
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('farmer.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Vérifier si l'utilisateur est l'agriculteur propriétaire du produit
        if (auth()->user()->role === 'farmer' && $product->farmer_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Vérifier si l'utilisateur est l'agriculteur propriétaire du produit
        if (auth()->user()->role === 'farmer' && $product->farmer_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'unit' => $validated['unit'],
            'category_id' => $validated['category_id'],
            'is_active' => $validated['is_active'] ?? $product->is_active
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('farmer.products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        // Vérifier si l'utilisateur est l'agriculteur propriétaire du produit
        if (auth()->user()->role === 'farmer' && $product->farmer_id !== auth()->id()) {
            abort(403, 'Accès non autorisé');
        }

        // Supprimer les images associées
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('farmer.products.index')->with('success', 'Produit supprimé avec succès.');
    }
}