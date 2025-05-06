<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Statistiques
        $todayOrders = Order::whereDate('created_at', today())->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        $query = Order::with(['user', 'products']);

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par date
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        if ($request->has('sort')) {
            $sort = $request->sort;
            switch ($sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'total_asc':
                    $query->orderBy('total', 'asc');
                    break;
                case 'total_desc':
                    $query->orderBy('total', 'desc');
                    break;
            }
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact(
            'orders',
            'todayOrders',
            'processingOrders',
            'pendingOrders',
            'monthlyRevenue'
        ));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash,card,transfer',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $validated['user_id'],
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'total_amount' => 0
            ]);

            $totalAmount = 0;
            foreach ($validated['products'] as $item) {
                $product = Product::find($item['id']);
                $quantity = $item['quantity'];
                $price = $product->price;

                $order->products()->attach($product->id, [
                    'quantity' => $quantity,
                    'price' => $price
                ]);

                $totalAmount += $price * $quantity;
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Commande créée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création de la commande.'])->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['user', 'products']);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $users = User::where('role', 'user')->get();
        $products = Product::where('is_available', true)->get();
        $order->load('products');
        return view('admin.orders.edit', compact('order', 'users', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash,card,transfer',
            'notes' => 'nullable|string'
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }
}