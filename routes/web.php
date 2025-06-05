<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->middleware(['auth', 'verified'])->name('orders');

Route::get('/create', [\App\Http\Controllers\OrderController::class, 'create'])->middleware(['auth', 'verified'])->name('order_create');

Route::post('/store', [\App\Http\Controllers\OrderController::class, 'store'])->middleware(['auth', 'verified'])->name('order.store');

Route::delete('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->middleware(['auth', 'verified'])->name('order.destroy');

Route::get('/orders/{order}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->middleware(['auth', 'verified'])->name('order.edit');

Route::patch('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'update'])->middleware(['auth', 'verified'])->name('order.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
