<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostController;
use App\Http\Middleware\OnlyAdminAllowed;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'welcome'], 200);
});

Route::group([
    'middleware' => [OnlyAdminAllowed::class, 'auth'],
], function () {
    Route::patch('/approve-user/{user_id}', [AdminController::class, 'approveUser'])->name('admin.approve');
    Route::patch('/revoke-user-approval/{user_id}', [AdminController::class, 'revokeUserApproval'])->name('admin.revoke');
});

Route::resource('/{tenant_id}/posts', PostController::class);

Route::resource('/{tenant_id}/comments', CommentController::class)->except(['destroy', 'index']);

Route::post('/{tenant_id}/blogs/edit', [BlogController::class, 'store'])->name('blog.edit');
Route::patch('/{tenant_id}/blogs/update', [BlogController::class, 'destroy'])->name('blog.update');

Route::post('/{tenant_id}/likes/store', [LikeController::class, 'store'])->name('likes.store');
Route::delete('/{tenant_id}/likes/delete', [LikeController::class, 'destroy'])->name('likes.destroy');
