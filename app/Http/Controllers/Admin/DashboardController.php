<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'farmers_count' => User::where('role', 'farmer')->count(),
            'products_count' => Product::count(),
            'recent_farmers' => User::where('role', 'farmer')
                ->latest()
                ->take(5)
                ->get(),
            'recent_products' => Product::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
