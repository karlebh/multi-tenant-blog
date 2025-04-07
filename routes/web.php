<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CantManageBlogUnlessApproved;
use App\Http\Middleware\CantPostUnlessApproved;
use App\Http\Middleware\OnlyAdminAllowed;
use App\Http\Middleware\OnlyAdminCanManageAllBlogs;
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

Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout')->middleware('auth');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('admin.logout')->middleware('auth:admin');

Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
Route::delete('/likes/{like}', [LikeController::class, 'destroy'])->name('likes.destroy');

Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::group([
    'middleware' => [OnlyAdminCanManageAllBlogs::class, 'auth']
], function () {
    Route::get('/{tenant}/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/{tenant}/posts', [PostController::class, 'index'])->name('posts.index');

    Route::middleware(CantManageBlogUnlessApproved::class)->group(function () {
        Route::post('/{tenant}/blogs/edit', [BlogController::class, 'store'])->name('blogs.edit');
        Route::patch('/{tenant}/blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.update');

        Route::get('/{tenant}/posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::get('/{tenant}/posts/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::post('/{tenant}/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{tenant}/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{tenant}/posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{tenant}/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});


require __DIR__ . '/auth.php';
