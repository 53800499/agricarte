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
    $user = auth()->user();

    // Valeurs par défaut (admin)
    $totalOrders = Order::count();
    $totalProducts = Product::count();
    $totalFarmers = User::where('role', 'farmer')->count();
    $totalUsers = User::where('role', 'user')->count();

    $productsInStock = Product::where('is_available', true)->count();
    $productsSold = Order::where('status', 'completed')->sum('total');
    $productsPending = Product::where('is_available', false)->count();

    $recentOrders = Order::with('user')->latest()->take(10)->get();

    // Si l'utilisateur est un agriculteur, filtrer ses données uniquement
    if ($user->role === 'farmer') {
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalProducts = Product::where('user_id', $user->id)->count();

        // Le reste n’a pas forcément de sens pour un fermier, on peut le neutraliser ou l’adapter :
        $totalFarmers = 1; // lui-même
        $totalUsers = 0;

        $productsInStock = Product::where('user_id', $user->id)
                                  ->where('is_available', true)
                                  ->count();

        $productsSold = Order::where('user_id', $user->id)
                             ->where('status', 'completed')
                             ->sum('total');

        $productsPending = Product::where('user_id', $user->id)
                                  ->where('is_available', false)
                                  ->count();

        $recentOrders = Order::with('user')
            ->where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();
    }

    return view('admin.dashboard', compact(
        'totalOrders',
        'totalProducts',
        'totalFarmers',
        'totalUsers',
        'productsInStock',
        'productsSold',
        'productsPending',
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