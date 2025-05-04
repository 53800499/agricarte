<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'farmer')
            ->where('is_active', true)
            ->with(['products.category']);

        // Filtre par distance si les coordonnées sont fournies
        if ($request->has(['latitude', 'longitude', 'radius'])) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 10); // Rayon par défaut de 10km

            $query->select('*')
                ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [
                    $latitude,
                    $longitude,
                    $latitude
                ])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        // Filtre par catégorie
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->whereHas('products.category', function ($q) use ($category) {
                $q->where('name', 'like', '%' . $category . '%');
            });
        }

        // Filtre par recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhereHas('products', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $producers = $query->get();
        $categories = Category::all();

        return view('map', compact('producers', 'categories'));
    }

    public function getProducers(Request $request)
    {
        $query = User::where('role', 'farmer')
            ->where('is_active', true)
            ->with(['products.category']);

        // Filtre par distance
        if ($request->has(['latitude', 'longitude', 'radius'])) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 10);

            $query->select('*')
                ->selectRaw('(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', [
                    $latitude,
                    $longitude,
                    $latitude
                ])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        $producers = $query->get();

        return response()->json([
            'producers' => $producers->map(function ($producer) {
                return [
                    'id' => $producer->id,
                    'name' => $producer->name,
                    'address' => $producer->address,
                    'latitude' => $producer->latitude,
                    'longitude' => $producer->longitude,
                    'profile_image' => $producer->profile_image ? asset('storage/' . $producer->profile_image) : asset('images/default-farmer.jpg'),
                    'products' => $producer->products->map(function ($product) {
                        return [
                            'name' => $product->name,
                            'category' => $product->category->name,
                            'price' => $product->price,
                            'unit' => $product->unit
                        ];
                    })
                ];
            })
        ]);
    }
}
