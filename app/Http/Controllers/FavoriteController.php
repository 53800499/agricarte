<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorite products.
     */
    public function index()
    {
        $favorites = Auth::user()->favoriteProducts()->with('user')->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add a product to favorites.
     */
    public function store(Product $product)
    {
        if (Auth::user()->favoriteProducts()->where('product_id', $product->id)->exists()) {
            return back()->with('warning', 'Ce produit est déjà dans vos favoris.');
        }

        Auth::user()->favoriteProducts()->attach($product->id);
        return back()->with('success', 'Produit ajouté aux favoris avec succès.');
    }

    /**
     * Remove a product from favorites.
     */
    public function destroy(Product $product)
    {
        Auth::user()->favoriteProducts()->detach($product->id);
        return back()->with('success', 'Produit retiré des favoris avec succès.');
    }

    /**
     * Toggle favorite status for a product.
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();
        if ($user->favoriteProducts()->where('product_id', $product->id)->exists()) {
            $user->favoriteProducts()->detach($product->id);
            return response()->json(['status' => 'removed', 'message' => 'Produit retiré des favoris']);
        } else {
            $user->favoriteProducts()->attach($product->id);
            return response()->json(['status' => 'added', 'message' => 'Produit ajouté aux favoris']);
        }
    }
}
