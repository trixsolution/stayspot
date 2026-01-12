<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomAuthController::class, 'login']);
    Route::get('/register', [CustomAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomAuthController::class, 'register']);
});

Route::post('/logout', [CustomAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    
    // Customer / Public Home
    Route::get('/home', [PropertyController::class, 'index'])->name('home');

    // User Profile
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Become a Host
    Route::post('/become-host', [PropertyController::class, 'becomeHost'])->name('become.host');

    // Seller Routes
    Route::prefix('seller')->group(function () {
        Route::get('/dashboard', [PropertyController::class, 'sellerDashboard']);
        Route::get('/properties/create', [PropertyController::class, 'create']);
        Route::post('/properties', [PropertyController::class, 'store']);
        Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('seller.properties.edit');
        Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('seller.properties.update');
    });

    // Booking & Property Details
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
    Route::post('/properties/{property}/book', [\App\Http\Controllers\BookingController::class, 'store'])->name('properties.book');
    Route::get('/my-bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::delete('/my-bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'destroy'])->name('bookings.destroy');

    // Delete Property (Seller or Admin)
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy']);

    // Admin Routes (Hidden URL)
    // Temp route to become admin
    Route::get('/make-me-admin', function () {
        $user = Auth::user();
        $user->role = 'admin';
        $user->save();
        return redirect('/secret-admin-portal')->with('success', 'You are now an admin!');
    });

});

// Admin Routes (Hidden URL) - Handled separately
Route::prefix('secret-admin-portal')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index'); // Added name
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    
    // Protected Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/users', [AdminController::class, 'users']);
        Route::get('/properties', [AdminController::class, 'properties']);
        Route::get('/bookings', [AdminController::class, 'bookings']);
        Route::get('/revenue', [AdminController::class, 'revenue']);
        Route::get('/profile', [AdminController::class, 'profile']);
        Route::put('/profile', [AdminController::class, 'updateProfile']);
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser']);
    });
});


