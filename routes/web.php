<?php

use App\Livewire\FamilyList;
use App\Livewire\SonList;
use App\Livewire\DaughterList;
use App\Livewire\FamilyManager;
use App\Livewire\FamilyDetails;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/families');
});

Route::get('/login', Login::class)->name('login');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/families', FamilyList::class);
    Route::get('/sons', SonList::class);
    Route::get('/daughters', DaughterList::class);
    Route::get('/create-family', FamilyManager::class);
    Route::get('/edit-family/{id}', FamilyManager::class);
    Route::get('/family/{id}', FamilyDetails::class);
});
