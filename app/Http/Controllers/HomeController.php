<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $heroSlides = [
            ['image' => 'images/slide1.jpg', 'title' => 'Produits frais', 'description' => 'Directement du champ à votre assiette.'],
            ['image' => 'images/slide2.jpg', 'title' => 'Agriculteurs locaux', 'description' => 'Soutenez l’agriculture de proximité.'],
            ['image' => 'images/slide3.jpg', 'title' => 'Qualité et saveur', 'description' => 'Des produits naturels et savoureux.'],
        ];

        $query = Product::query()
            ->with(['user', 'category'])
            ->where('is_available', true)
            ->where('is_featured', true);

        // Filtre par catégorie
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtre par distance
        if ($request->has('distance') && $request->distance !== 'all' && Auth::check()) {
            $user = Auth::user();
            $query->whereHas('user', function ($q) use ($user, $request) {
                $q->whereRaw("
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * sin(radians(latitude)))) <= ?
                ", [$user->latitude, $user->longitude, $user->latitude, $request->distance]);
            });
        }

        // Tri par prix
        if ($request->has('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $featuredProducts = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('home', compact('featuredProducts', 'categories','heroSlides'));
    }
}