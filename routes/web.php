<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\OnlyAdminAllowed;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group([
    'as' => 'admin.',
    'middleware' => [OnlyAdminAllowed::class, 'auth'],
], function () {
    Route::patch('/approve-user', [AdminController::class, 'approveUser']);
    Route::patch('/revoke-user-approval', [AdminController::class, 'revokeUserApproval']);
});

Route::group([
    'middleware' => ['auth'],
], function () {
    Route::resource('/post', PostController::class);
});
