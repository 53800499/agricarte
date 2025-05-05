<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ProductController,
    ContactController,
    MapController,
    AuthController,
    ProducerController
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
use App\Http\Controllers\CartController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Routes publiques
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produits', [ProductController::class, 'index'])->name('products');
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Routes du panier
Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Routes de la newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

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

    // Carte
    Route::get('/carte', [MapController::class, 'index'])->name('map');

    // Profil utilisateur
    Route::get('/profile', [UserSettingController::class, 'index'])->name('profile');
    Route::put('/profile', [UserSettingController::class, 'updateProfile'])->name('profile.update');

    // Routes pour les favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/favorites/{product}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // Routes des paramètres utilisateur
    Route::get('/settings', [UserSettingController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [UserSettingController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [UserSettingController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [UserSettingController::class, 'updateNotifications'])->name('settings.notifications.update');
    Route::put('/settings/privacy', [UserSettingController::class, 'updatePrivacy'])->name('settings.privacy.update');
    Route::delete('/settings', [UserSettingController::class, 'destroy'])->name('settings.destroy');

    /*
    |--------------------------------------------------------------------------
    | Espace Admin
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Produits
        Route::get('/products', [ProductControllers::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [ProductControllers::class, 'create'])->name('admin.products.create');
        Route::post('/product', [ProductControllers::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}', [ProductControllers::class, 'show'])->name('admin.products.show');
        Route::get('/products/{product}/edit', [ProductControllers::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{product}', [ProductControllers::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [ProductControllers::class, 'destroy'])->name('admin.products.destroy');

        // Agriculteurs
        Route::get('/farmers', [FarmerController::class, 'index'])->name('admin.farmers.index');
        Route::get('/farmers/create', [FarmerController::class, 'create'])->name('admin.farmers.create');
        Route::post('/farmers', [FarmerController::class, 'store'])->name('admin.farmers.store');
        Route::get('/farmers/{farmer}', [FarmerController::class, 'show'])->name('admin.farmers.show');
        Route::get('/farmers/{farmer}/edit', [FarmerController::class, 'edit'])->name('admin.farmers.edit');
        Route::put('/farmers/{farmer}', [FarmerController::class, 'update'])->name('admin.farmers.update');
        Route::delete('/farmers/{farmer}', [FarmerController::class, 'destroy'])->name('admin.farmers.destroy');

        // Catégories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Commandes
        Route::resource('orders', OrderController::class)->except(['create', 'store'])->names([
            'index' => 'admin.orders.index',
            'show' => 'admin.orders.show',
            'edit' => 'admin.orders.edit',
            'update' => 'admin.orders.update',
            'destroy' => 'admin.orders.destroy'
        ]);

        // Utilisateurs
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Paramètres admin
        Route::put('/admin/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    });

    /*
    |--------------------------------------------------------------------------
    | Espace Agriculteur
    |--------------------------------------------------------------------------
    */
    Route::prefix('farmer')->middleware('farmer')->group(function () {
        // Produits de l'agriculteur
        Route::get('/products', [ProductControllers::class, 'index'])->name('farmer.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('farmer.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('farmer.products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('farmer.products.show');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('farmer.products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('farmer.products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('farmer.products.destroy');

        // Commandes de l'agriculteur
        Route::get('/orders', [OrderController::class, 'index'])->name('farmer.orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('farmer.orders.show');
        Route::put('/orders/{order}', [OrderController::class, 'update'])->name('farmer.orders.update');

        // Profil de l'agriculteur
        Route::get('/profile', [FarmerController::class, 'show'])->name('farmer.profile');
        Route::put('/profile', [FarmerController::class, 'update'])->name('farmer.profile.update');
    });
});

// Map routes
Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/api/producers', [MapController::class, 'getProducers'])->name('map.producers');
Route::get('/producers/{producer}', [ProducerController::class, 'show'])->name('producers.show');

// Routes publiques pour les produits
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Routes admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes pour les produits
    Route::resource('products', ProductControllers::class);

    // Routes pour les agriculteurs
    Route::resource('farmers', FarmerController::class);

    // Routes pour les catégories
    Route::resource('categories', CategoryController::class);

    // Routes pour les commandes
    Route::resource('orders', OrderController::class);

    // Routes pour les utilisateurs
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy'
    ]);

    // Routes pour le profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour les paramètres
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

// Routes pour les producteurs
Route::middleware(['auth', 'farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    // Produits
    Route::get('/products', [FarmerController::class, 'index'])->name('products.index');
    Route::get('/products/create', [FarmerController::class, 'create'])->name('products.create');
    Route::post('/products', [FarmerController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [FarmerController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [FarmerController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [FarmerController::class, 'destroy'])->name('products.destroy');

    // Commandes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Profil
    Route::get('/profile', [FarmerController::class, 'show'])->name('profile');
    Route::put('/profile', [FarmerController::class, 'update'])->name('profile.update');
});

// Routes pour les commandes
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Routes de commande
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});