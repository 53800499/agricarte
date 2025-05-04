<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'farmer') {
            // Si l'utilisateur est un agriculteur
            if ($request->route()->named('farmers.*')) {
                // Vérifier si l'agriculteur accède à ses propres données
                $farmerId = $request->route('farmer');
                if ($farmerId && $farmerId != Auth::id()) {
                    abort(403, 'Accès non autorisé');
                }
            }

            // Autoriser l'accès aux produits qu'il a créés
            if ($request->route()->named('products.*')) {
                $productId = $request->route('product');
                if ($productId) {
                    $product = \App\Models\Product::findOrFail($productId);
                    if ($product->farmer_id != Auth::id()) {
                        abort(403, 'Accès non autorisé');
                    }
                }
            }

            // Autoriser l'accès aux commandes qui le concernent
            if ($request->route()->named('orders.*')) {
                $orderId = $request->route('order');
                if ($orderId) {
                    $order = \App\Models\Order::findOrFail($orderId);
                    if (!$order->products()->where('farmer_id', Auth::id())->exists()) {
                        abort(403, 'Accès non autorisé');
                    }
                }
            }

            return $next($request);
        }

        abort(403, 'Accès non autorisé');
    }
}
