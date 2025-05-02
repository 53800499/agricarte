<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = [
            [
                'id' => 1,
                'name' => 'Laitue',
                'image' => 'images/products/tomates.jpg',
                'price' => '2.50',
                'unit' => 'pièce',
                'producer' => 'Ferme des Oliviers',
                'category' => 'Légumes'
            ],
            [
                'id' => 2,
                'name' => 'Tomate',
                'image' => 'images/products/tomates.jpg',
                'price' => '3.20',
                'unit' => 'kg',
                'producer' => 'Jardins de Provence',
                'category' => 'Légumes'
            ],
            [
                'id' => 3,
                'name' => 'Oignon rouge',
                'image' => 'images/products/tomates.jpg',
                'price' => '2.80',
                'unit' => 'kg',
                'producer' => 'Potager Bio',
                'category' => 'Légumes'
            ],
            [
                'id' => 4,
                'name' => 'Pomme verte',
                'image' => 'images/products/tomates.jpg',
                'price' => '3.50',
                'unit' => 'kg',
                'producer' => 'Vergers du Sud',
                'category' => 'Fruits'
            ],
            [
                'id' => 5,
                'name' => 'Carottes',
                'image' => 'images/products/tomates.jpg',
                'price' => '2.30',
                'unit' => 'kg',
                'producer' => 'Ferme des Oliviers',
                'category' => 'Légumes'
            ],
            [
                'id' => 6,
                'name' => 'Pomme de terre',
                'image' => 'images/products/tomates.jpg',
                'price' => '1.90',
                'unit' => 'kg',
                'producer' => 'Potager Bio',
                'category' => 'Légumes'
            ]
        ];

        $categories = ['Tous', 'Légumes', 'Fruits', 'Produits laitiers', 'Viandes'];

        return view('products', compact('products', 'categories'));
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'farmer_id' => 'required|exists:users,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return $product->load('farmer:id,name');
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

        $product->update($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }

}