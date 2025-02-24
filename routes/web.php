<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractorProfileController;
use App\Http\Controllers\PostController;

Route::middleware('auth')->group(function () {
    // Routes untuk profil user biasa
    Route::get('/user/{id}/profile', [ProfileController::class, 'showPublic'])->name('user.profile.show');
    Route::get('/contractors', [ContractorProfileController::class, 'index'])->name('contractors.index');
    // Routes untuk profil kontraktor
    Route::get('/contractor/{id}/profile', [ContractorProfileController::class, 'showPublic'])->name('contractor.profile.showPublic');    // Routes lainnya...
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/all', [PostController::class, 'all'])->name('posts.all');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{id}/comment', [PostController::class, 'comment'])->name('posts.comment');
});
Route::middleware('auth')->group(function () {
    Route::get('/contractor/profile', [ContractorProfileController::class, 'show'])->name('contractor.profile.show');
    Route::get('/contractor/profile/edit', [ContractorProfileController::class, 'edit'])->name('contractor.profile.edit');
    Route::post('/contractor/profile/update', [ContractorProfileController::class, 'update'])->name('contractor.profile.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home'); // Tambahkan nama route 'home'
});
Route::get('/', function () {
    return view('welcome');
});
