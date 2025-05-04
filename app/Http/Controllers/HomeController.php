<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();
        // Get featured products (you can customize this query based on your needs)
        $featuredProducts = Product::with(['category', 'user'])
            ->where('is_featured', true)
            ->where('is_available', true)
            ->latest()
            ->take(6)
            ->get();

        Log::info($featuredProducts);

        return view('home', compact('featuredProducts', 'categories'));
    }
}