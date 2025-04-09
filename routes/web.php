<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\AdminCanSeeAllPosts;
use App\Http\Middleware\CantManageBlogUnlessApproved;
use App\Http\Middleware\MultiGuardAuth;
use App\Http\Middleware\OnlyAdminAllowed;
use App\Http\Middleware\OnlyAdminCanManageAllBlogs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::group([
    'middleware' => [OnlyAdminAllowed::class, 'auth:admin'],
], function () {
    Route::post('/approve-user/{tenant}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::post('/revoke-user-approval/{tenant}', [AdminController::class, 'revokeUserApproval'])->name('admin.revoke');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

Route::group([
    'middleware' => 'guest'
], function () {
    Route::get('/admin/login', [AuthController::class, 'adminLoginForm'])->name('admin.login.form');
    Route::get('/admin/register', [AuthController::class, 'adminRegisterForm'])->name('admin.register.form');
    Route::post('/admin/register', [AuthController::class, 'AdminRegsiter'])->name('admin.register');
    Route::post('/admin/login', [AuthController::class, 'AdminLogin'])->name('admin.login');

    Route::post('/user/register', [AuthController::class, 'userRegister'])->name('user.register');
    Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login');
});

Route::get('/{tenant}/blogs', [BlogController::class, 'index'])->name('blogs.index')->middleware(MultiGuardAuth::class);

Route::middleware([
    CantManageBlogUnlessApproved::class,
    MultiGuardAuth::class,
    OnlyAdminCanManageAllBlogs::class,
])
    ->group(function () {
        Route::get('/{tenant}/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::put('/{tenant}/blogs/{blog}', [BlogController::class, 'update'])->name('blogs.update');

        Route::get('/{tenant}/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::get('/{tenant}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::post('/{tenant}/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{tenant}/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{tenant}/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{tenant}/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout')->middleware('auth:admin');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');




require __DIR__ . '/auth.php';
