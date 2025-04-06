<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/{tenant_id}/blogs', [BlogController::class, 'index'])->name('blog.index');
Route::post('/{tenant_id}/blogs/edit', [BlogController::class, 'store'])->name('blog.edit');
Route::patch('/{tenant_id}/blogs', [BlogController::class, 'destroy'])->name('blog.update');






require __DIR__ . '/auth.php';
