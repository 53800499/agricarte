<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        // Vérifier si le produit est disponible
        if (!$product->is_available || $product->stock_quantity < $quantity) {
            return back()->with('error', 'Ce produit n\'est pas disponible en quantité suffisante.');
        }

        // Récupérer le panier de la session
        $cart = Session::get('cart', []);

        // Vérifier si le produit est déjà dans le panier
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'image' => $product->image,
                'unit' => $product->unit
            ];
        }

        // Mettre à jour le panier dans la session
        Session::put('cart', $cart);

        return back()->with('success', 'Produit ajouté au panier avec succès.');
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function update(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if ($quantity <= 0) {
            return $this->remove($product);
        }

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Quantité mise à jour avec succès.');
    }

    public function remove(Product $product)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Produit retiré du panier avec succès.');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Panier vidé avec succès.');
    }
}
