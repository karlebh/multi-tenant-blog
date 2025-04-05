<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveUser(User $tenant) {}
    public function revokeUserApproval(User $tenant) {}
}
