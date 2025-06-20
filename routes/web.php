<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractorProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminContractorController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;


Route::middleware('auth')->group(function () {
    Route::get('/offers/my-offers', [OfferController::class, 'myOffers'])->name('offers.my-offers');
    Route::post('orders/{id}/payment/{type}/stage/{stage}', [OrderController::class, 'uploadPaymentProof'])->name('orders.uploadPaymentProof');
    Route::post('/bookings/{id}/final-approve', [BookingController::class, 'finalApprove'])->name('bookings.finalApprove');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->middleware('auth');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->middleware('auth');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::patch('/admin/contractors/{id}/approve', [DashboardController::class, 'approve'])->name('admin.contractors.approve');
    Route::patch('/admin/contractors/{id}/reject', [DashboardController::class, 'reject'])->name('admin.contractors.reject');
    Route::get('/admin/contractors/{id}', [DashboardController::class, 'show'])->name('admin.contractors.show'); // Belum diimplementasikan
    Route::get('/admin/bookings/{id}', [DashboardController::class, 'showBooking'])->name('admin.bookings.show'); // Detail booking
    Route::get('/admin/bookings', [DashboardController::class, 'indexBookings'])->name('admin.bookings.index'); // Daftar semua booking
    // Routes untuk subscription
    Route::get('/recommendations', [RecommendationController::class, 'getRecommendations'])->name('recommendations.index');
    Route::post('/orders/{orderId}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::post('/bookings/{bookingId}/complete', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/orders/{orderId}/review', [OrderController::class, 'storeReview'])->name('orders.review');
    Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
    Route::get('/subscriptions/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::get('/subscriptions/failed', [SubscriptionController::class, 'failed'])->name('subscriptions.failed');

    // Routes lainnya...
    Route::get('/bookings/contractor/{contractorId}/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/contractor/{contractorId}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings/{bookingId}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    // Routes untuk orders
    Route::post('/orders/from-offer/{offerId}', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/posts/{postId}/offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('/offers/{offerId}/accept', [OfferController::class, 'accept'])->name('offers.accept');
    Route::post('/posts/{postId}/offers', [OfferController::class, 'store'])->name('offers.store');
    Route::get('/admin/contractors', [AdminContractorController::class, 'index'])->name('admin.contractors.index');
    Route::post('/admin/contractors/{id}/approve', [AdminContractorController::class, 'approve'])->name('admin.contractors.approve');
    // Routes untuk profil user biasa
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{receiverId?}', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/chats/{receiverId}', [ChatController::class, 'store'])->name('chats.store');
    Route::delete('/contractor/profile/delete/{type}/{index}', [ContractorProfileController::class, 'deleteFile'])->name('contractor.profile.delete');

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
Route::get('/home', [PostController::class, 'getLatestTenders'])->name('home');
});

Route::get('/', [ContractorProfileController::class, 'welcome'])->name('welcome');
