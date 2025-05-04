<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques de base
        $todayOrders = Order::whereDate('created_at', today())->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        // Données pour le graphique des ventes (30 derniers jours)
        $salesData = [
            'labels' => [],
            'values' => []
        ];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesData['labels'][] = $date->format('d/m');
            $salesData['values'][] = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total');
        }

        // Produits les plus vendus
        $topProducts = Product::withCount(['orders as total_sales' => function($query) {
            $query->where('status', 'completed');
        }])
        ->orderByDesc('total_sales')
        ->take(5)
        ->get();

        // Dernières commandes
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'todayOrders',
            'totalProducts',
            'totalCustomers',
            'monthlyRevenue',
            'salesData',
            'topProducts',
            'recentOrders'
        ));
    }
    public function showProfil(Request $request, $user)
    {
        $user = User::find($user);
        return view('admin.profile', compact('user'));
    }
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-\+\(\)]{10,20}$/'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:user,farmer'],
            'terms' => ['required', 'accepted'],
            'address' => ['required_if:role,farmer', 'string', 'max:255', 'nullable'],
            'latitude' => ['required_if:role,farmer', 'numeric', 'between:-90,90', 'nullable'],
            'longitude' => ['required_if:role,farmer', 'numeric', 'between:-180,180', 'nullable'],

        ], [
            'phone.regex' => 'Le format du numéro de téléphone est invalide.',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($product->profile_image) {
                Storage::disk('public')->delete($product->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('products', 'public');
        } elseif ($request->remove_profile_image) {
            if ($product->profile_image) {
                Storage::disk('public')->delete($product->profile_image);
            }
            $validated['profile_image'] = null;
        }

        $product->update($validated);

        return response()->json(['success' => true]);
    }
}
