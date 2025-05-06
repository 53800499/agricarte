<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity']
                ];
                $total += $product->price * $item['quantity'];
            }
        }

        return view('checkout.index', compact('products', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash,card,transfer',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'total' => 0
            ]);

            $totalAmount = 0;
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                if ($product) {
                    $quantity = $item['quantity'];
                    $price = $product->price;

                    // Vérifier le stock
                    if ($product->stock_quantity < $quantity) {
                        throw new \Exception("Stock insuffisant pour le produit: {$product->name}");
                    }

                    // Mettre à jour le stock
                    $product->decrement('stock_quantity', $quantity);

                    $order->products()->attach($product->id, [
                        'quantity' => $quantity,
                        'price' => $price
                    ]);

                    $totalAmount += $price * $quantity;
                }
            }

            $order->update(['total' => $totalAmount]);

            // Vider le panier
            session()->forget('cart');

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Votre commande a été passée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la commande: ' . $e->getMessage())
                ->withInput();
        }
    }
} 