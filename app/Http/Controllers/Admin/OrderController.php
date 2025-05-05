<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'products']);
        // Si l'utilisateur est un agriculteur, ne montrer que les commandes contenant ses produits
        if (auth()->user()->role === 'farmer') {
            $orders->whereHas('products', function($query) {
                $query->where('user_id', auth()->id());
            });
        }
        $orders = $orders->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'products']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Statut de la commande mis à jour avec succès');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Commande supprimée avec succès');
    }
}
