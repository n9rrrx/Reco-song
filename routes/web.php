<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. CORE APPLICATION ROUTES ---

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/recognize', [HomeController::class, 'recognize'])->name('recognize');

// Recognition API Endpoint
Route::post('/recognize', [HomeController::class, 'recognize'])->name('recognize');


// --- 2. TEMPLATE PAGES ---
Route::get('/blog', function () { return view('blog'); })->name('blog');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');


// --- 3. APP FEATURES ---
Route::get('/history', function () { return view('history'); })->name('history');
Route::get('/trending', function () { return view('trending'); })->name('trending');
Route::get('/profile', function () { return view('profile'); })->name('profile');


// --- 4. AUTHENTICATION (Fixed) ---

// We use a Closure (function) to load the view directly,
// so we don't need the Controllers yet.
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');


