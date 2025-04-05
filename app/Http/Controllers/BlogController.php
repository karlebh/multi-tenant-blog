<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function edit(User $tenant) {}
    public function update(Request $request, User $tenant) {}
}
