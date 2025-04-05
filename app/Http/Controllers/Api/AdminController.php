<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveUser(int $user_id) {}
    public function revokeUserApproval(int $user_id) {}
}
