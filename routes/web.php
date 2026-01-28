<?php

use App\Livewire\FamilyList;
use App\Livewire\SonList;
use App\Livewire\DaughterList;
use App\Livewire\FamilyManager;
use App\Livewire\FamilyDetails;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ============================================
// PUBLIC WEBSITE ROUTES
// ============================================
Route::get('/', function () {
    return view('website.home');
})->name('home');

// Add more public routes here as needed
// Route::get('/about', function () { return view('website.about'); })->name('about');
// Route::get('/contact', function () { return view('website.contact'); })->name('contact');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
Route::get('/login', Login::class)->name('login');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ============================================
// DASHBOARD ROUTES (Admin Panel)
// ============================================
Route::prefix('dashboard')->middleware('auth')->name('dashboard.')->group(function () {
    // Dashboard home - redirect to families list
    Route::get('/', function () {
        return redirect()->route('dashboard.families');
    })->name('home');
    
    // Family Management
    Route::get('/families', FamilyList::class)->name('families');
    Route::get('/create-family', FamilyManager::class)->name('create-family');
    Route::get('/edit-family/{id}', FamilyManager::class)->name('edit-family');
    Route::get('/family/{id}', FamilyDetails::class)->name('family-details');
    
    // Children Management
    Route::get('/sons', SonList::class)->name('sons');
    Route::get('/daughters', DaughterList::class)->name('daughters');
});
