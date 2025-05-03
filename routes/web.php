<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductControllers;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\FarmerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Pages produits
Route::get('/produits', [ProductController::class, 'index'])->name('products');


// Page contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Authentification
Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Inscription
Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/inscription', [RegisterController::class, 'register']);

//Route::get('/admin', [DashboardController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile/{user}', [DashboardController::class, 'showProfil'])->name('profile');
    Route::put('/profile/{user}', [DashboardController::class, 'showProfil'])->name('profile.update');

    // Page carte
    Route::get('/carte', [MapController::class, 'index'])->name('map');

    // Routes pour les agriculteurs
    Route::prefix('admin')->middleware(['auth'])->group(function () {
        Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
        Route::post('/farmers', [FarmerController::class, 'store'])->name('farmers.store');
        Route::get('/farmers/{farmer}', [FarmerController::class, 'show'])->name('farmers.show');
        Route::put('/farmers/{farmer}', [FarmerController::class, 'update'])->name('farmers.update');
        Route::delete('/farmers/{farmer}', [FarmerController::class, 'destroy'])->name('farmers.destroy');
    });

    // Routes pour les produits
    Route::get('/admin/ products', [ProductControllers::class, 'index'])->name('products.index');
    Route::post('/admin/products', [ProductControllers::class, 'store'])->name('products.store');
    Route::get('/admin/products/{product}', [ProductControllers::class, 'show'])->name('products.show');
    Route::put('/admin/products/{product}', [ProductControllers::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{product}', [ProductControllers::class, 'destroy'])->name('products.destroy');
});
