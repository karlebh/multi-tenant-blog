<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\OnlyAdminAllowed;
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


Route::group([
    'middleware' => [OnlyAdminAllowed::class, 'auth'],
], function () {
    Route::patch('/approve-user/{user_id}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::patch('/revoke-user-approval/{user_id}', [AdminController::class, 'revokeUserApproval'])->name('admin.revoke');
});

Route::get('/{tenant_id}/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::post('/{tenant_id}/blogs/edit', [BlogController::class, 'store'])->name('blogs.edit');
Route::patch('/{tenant_id}/blogs', [BlogController::class, 'destroy'])->name('blogs.update');

Route::get('/{tenant_id}/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/{tenant_id}/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/{tenant_id}/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/{tenant_id}/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/{tenant_id}/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/{tenant_id}/posts/{id}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/{tenant_id}/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');

// Route::get('{blog_id}/posts', [PostController::class, 'index'])->name('posts.index');
// Route::get('{blog_id}/posts/create', [PostController::class, 'create'])->name('posts.create');
// Route::post('{blog_id}/posts', [PostController::class, 'store'])->name('posts.store');
// Route::get('{blog_id}/posts/{id}', [PostController::class, 'show'])->name('posts.show');
// Route::get('{blog_id}/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
// Route::put('{blog_id}/posts/{id}', [PostController::class, 'update'])->name('posts.update');
// Route::delete('{blog_id}/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');


Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
Route::delete('/likes/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');

Route::post('/{tenant_id}/comment', [CommentController::class, 'store'])->name('comment.store');
Route::get('/{tenant_id}/comment/{comment_id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
Route::put('/{tenant_id}/comment/{comment_id}', [CommentController::class, 'update'])->name('comment.update');
Route::delete('/{tenant_id}/comment/{comment_id}', [CommentController::class, 'destroy'])->name('comment.destroy');

Route::get('user/login', [AuthController::class, 'userLoginForm'])->name('user.login.form');
Route::get('user/register', [AuthController::class, 'userRegisterForm'])->name('user.register.form');
Route::post('user/register', [AuthController::class, 'userRegister'])->name('user.register');
Route::post('user/login', [AuthController::class, 'userLogin'])->name('user.login');
Route::post('user/logout', [AuthController::class, 'userLogout'])->name('user.logout');

Route::get('admin/login', [AuthController::class, 'adminLoginForm'])->name('admin.login.form');
Route::get('admin/register', [AuthController::class, 'adminRegisterForm'])->name('admin.register.form');
Route::post('admin/register', [AuthController::class, 'AdminRegsiter'])->name('admin.register');
Route::post('admin/login', [AuthController::class, 'AdminLogin'])->name('admin.login');
Route::post('admin/logout', [AuthController::class, 'AdminLogout'])->name('admin.logout');










require __DIR__ . '/auth.php';
