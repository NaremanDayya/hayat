<?php

use App\Livewire\FamilyList;
use App\Livewire\SonList;
use App\Livewire\DaughterList;
use App\Livewire\FamilyManager;
use App\Livewire\FamilyDetails;
use App\Livewire\HealthConditionList;
use App\Livewire\Login;
use App\Livewire\PostList;
use App\Livewire\PostManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

// ============================================
// PUBLIC WEBSITE ROUTES
// ============================================
Route::get('/', function () {
    return view('website.home');
})->name('home');

// Activities (Public)
Route::get('/activities', function () {
    $query = Post::active()->ordered();
    if (request('type')) {
        $query->where('type', request('type'));
    }
    $posts = $query->paginate(12);
    return view('website.activities', compact('posts'));
})->name('activities');

Route::get('/activities/{slug}', function ($slug) {
    $post = Post::active()->where('slug', $slug)->with('images')->firstOrFail();
    return view('website.activity-detail', compact('post'));
})->name('activity.show');

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
    
    // Special Health Cases
    Route::get('/special-health-cases', HealthConditionList::class)->name('health-conditions');
    
    // Posts / Activities Management
    Route::get('/posts', PostList::class)->name('posts');
    Route::get('/create-post', PostManager::class)->name('create-post');
    Route::get('/edit-post/{id}', PostManager::class)->name('edit-post');
});
