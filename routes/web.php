<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    ContactController,
    MapController,
    AuthController
};
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\{
    DashboardController,
    FarmerController,
    ProductControllers,
    CategoryController,
    OrderController,
    UserController,
    SettingController
};
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserSettingController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produits', [ProductController::class, 'index'])->name('products');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| Authentification
|--------------------------------------------------------------------------
*/

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/inscription', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| Routes Authentifiées
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Dashboard (utilisé pour tous les rôles)
    Route::get('/admin', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::get('/profile/{user}', [DashboardController::class, 'showProfil'])->name('profile');
    Route::put('/profile/{user}', [DashboardController::class, 'showProfil'])->name('profile.update');

    // Carte
    Route::get('/carte', [MapController::class, 'index'])->name('map');

    /*
    |--------------------------------------------------------------------------
    | Espace Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {

        // Produits
        Route::get('/products', [ProductControllers::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductControllers::class, 'create'])->name('products.create');
        Route::post('/products', [ProductControllers::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductControllers::class, 'show'])->name('products.show');
        Route::get('/products/{product}/edit', [ProductControllers::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductControllers::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductControllers::class, 'destroy'])->name('products.destroy');

        // Agriculteurs
        Route::get('/farmers', [FarmerController::class, 'index'])->name('farmers.index');
        Route::post('/farmers', [FarmerController::class, 'store'])->name('farmers.store');
        Route::get('/farmers/{farmer}', [FarmerController::class, 'show'])->name('farmers.show');
        Route::put('/farmers/{farmer}', [FarmerController::class, 'update'])->name('farmers.update');
        Route::delete('/farmers/{farmer}', [FarmerController::class, 'destroy'])->name('farmers.destroy');

        // Catégories
        Route::resource('categories', CategoryController::class);

        // Commandes (sans create/store)
        Route::resource('orders', OrderController::class)->except(['create', 'store']);

        // Utilisateurs
        Route::resource('users', UserController::class);

        // Paramètres
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Routes pour les favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/{product}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// Routes protégées nécessitant une vérification d'email
Route::middleware(['auth', 'email.verified'])->group(function () {
    // Routes des favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/{product}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Routes des paramètres utilisateur
    Route::get('/settings', [UserSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [UserSettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [UserSettingController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [UserSettingController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::put('/settings/privacy', [UserSettingController::class, 'updatePrivacy'])->name('settings.privacy.update');
    Route::delete('/settings', [UserSettingController::class, 'destroy'])->name('settings.destroy');
});
