<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\LaptopCatalogController;
use App\Http\Controllers\LaptopController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. Public Catalog & Detail View (No Auth Required)
// ==========================================
Route::get('/', [LaptopCatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog', [LaptopCatalogController::class, 'index']);
Route::get('/catalog/{laptop}', [LaptopCatalogController::class, 'show'])->name('catalog.show');

// AI Laptop Recommendation Chat
Route::post('/chat/recommend', [AiChatController::class, 'recommend'])
    ->middleware('throttle:30,1')
    ->name('chat.recommend');

// ==========================================
// 2. Admin Authentication (/admin)
// ==========================================
// Hidden login screen: only accessible via /admin
Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Aliases / Redirects for legacy login and dashboard paths
Route::get('/login', fn () => redirect()->route('login'));
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout']);
Route::get('/inventory', fn () => redirect()->route('laptops.index'));
Route::get('/home', fn () => redirect()->route('laptops.index'))->name('home');
Route::get('/dashboard', fn () => redirect()->route('laptops.index'))->name('dashboard');

// ==========================================
// 3. Admin Inventory Management (CRUD) (Auth Required)
// ==========================================
Route::middleware('auth')->prefix('admin/inventory')->name('laptops.')->group(function () {
    Route::get('/', [LaptopController::class, 'index'])->name('index');
    Route::get('/create', [LaptopController::class, 'create'])->name('create');
    Route::post('/', [LaptopController::class, 'store'])->name('store');
    Route::get('/{laptop}', [LaptopController::class, 'show'])->name('show');
    Route::get('/{laptop}/edit', [LaptopController::class, 'edit'])->name('edit');
    Route::match(['put', 'patch'], '/{laptop}', [LaptopController::class, 'update'])->name('update');
    Route::delete('/{laptop}', [LaptopController::class, 'destroy'])->name('destroy');
    Route::patch('/{laptop}/toggle-status', [LaptopController::class, 'toggleAvailability'])->name('toggle');
});

// ==========================================
// 4. Admin Profile & Account Settings (Auth Required)
// ==========================================
Route::middleware('auth')->prefix('admin/profile')->name('admin.profile.')->group(function () {
    Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
    Route::put('/', [AdminProfileController::class, 'update'])->name('update');
    Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password');
});

// ==========================================
// 5. Admin Management (Main Admin Only)
// ==========================================
Route::middleware(['auth', 'main_admin'])->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [AdminUserController::class, 'index'])->name('index');
    Route::get('/create', [AdminUserController::class, 'create'])->name('create');
    Route::post('/', [AdminUserController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
    Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
});
