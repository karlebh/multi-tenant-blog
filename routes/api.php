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
    'middleware' => [OnlyAdminAllowed::class, 'auth:sanctum', 'ability:manage-users'],
], function () {
    Route::put('/admin/approve-user/{user_id}', [AdminController::class, 'approveUser']);
    Route::put('/admin/revoke-user-approval/{user_id}', [AdminController::class, 'revokeUserApproval']);
});

Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->middleware(['auth:sanctum', 'ability:manage-users']);
Route::post('/logout', [AuthController::class, 'userLogout'])->middleware('auth:sanctum');

Route::group(['middleware' => APIGuest::class], function () {
    Route::post('/register', [AuthController::class, 'userRegister']);
    Route::post('/login', [AuthController::class, 'userLogin']);
    Route::post('/admin/login', [AuthController::class, 'adminLogin']);
    Route::post('/admin/register', [AuthController::class, 'adminRegister']);
});

Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{comment_id}', [CommentController::class, 'update']);
Route::delete('/comments/{comment_id}', [CommentController::class, 'destroy'])->middleware(['auth:sanctum', 'ability:manage-users']);

Route::group(['middleware' => [
    'auth:sanctum',
    CantManageBlogUnlessApproved::class,
    OnlyAdminCanManageAllBlogs::class,
]], function () {
    Route::get('/{tenant_id}/posts/{post_id}', [PostController::class, 'show']);
    Route::post('/{tenant_id}/posts', [PostController::class, 'store']);
    Route::post('/{tenant_id}/posts/{post_id}', [PostController::class, 'update']);
    Route::delete('/{tenant_id}/posts/{post_id}', [PostController::class, 'destroy']);

    Route::get('/{tenant_id}/blogs', [BlogController::class, 'index']);
    Route::post('/{tenant_id}/blogs', [BlogController::class, 'update']);
});
