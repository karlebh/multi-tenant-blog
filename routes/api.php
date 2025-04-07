<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Middleware\APIGuest;
use App\Http\Middleware\CantManageBlogUnlessApproved;
use App\Http\Middleware\OnlyAdminAllowed;
use App\Http\Middleware\OnlyAdminCanManageAllBlogs;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'welcome'], 200);
});

Route::group([
    'middleware' => [OnlyAdminAllowed::class, 'ability:admin'],
], function () {
    Route::patch('/approve-user/{user_id}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::patch('/revoke-user-approval/{user_id}', [AdminController::class, 'revokeUserApproval'])->name('admin.revoke');
});

Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->middleware('ability:admin');
Route::post('/logout', [AuthController::class, 'userLogout'])->middleware('auth:sanctum');

Route::group(['middleware' => APIGuest::class], function () {
    Route::post('/register', [AuthController::class, 'userRegister']);
    Route::post('/login', [AuthController::class, 'userLogin']);
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
    Route::post('/admin/register', [AuthController::class, 'adminRegister']);
});

Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
Route::delete('/likes/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');

Route::post('/{tenant_id}/comments', [CommentController::class, 'store'])->name('comment.store');
Route::patch('/{tenant_id}/comments/{post_id}', [CommentController::class, 'update'])->name('comment.update');
Route::delete('/{tenant_id}/comments/{post_id}', [CommentController::class, 'destroy'])->name('comment.destroy');

Route::group(['middleware' => [
    'auth:sanctum',
    CantManageBlogUnlessApproved::class,
    OnlyAdminCanManageAllBlogs::class,
    // 'ability:admin'
]], function () {
    Route::get('/{tenant_id}/posts', [PostController::class, 'index'])->name('post.index');
    Route::get('/{tenant_id}/posts/{post_id}', [PostController::class, 'show'])->name('post.show');
    Route::post('/{tenant_id}/posts', [PostController::class, 'store'])->name('post.store');
    Route::patch('/{tenant_id}/posts/{post_id}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/{tenant_id}/posts/{post_id}', [PostController::class, 'destroy'])->name('post.destroy');

    Route::post('/{tenant_id}/blogs/edit', [BlogController::class, 'store'])->name('blog.edit');
    Route::patch('/{tenant_id}/blogs', [BlogController::class, 'destroy'])->name('blog.update');
});
