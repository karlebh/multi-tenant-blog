<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(User $tenant) {}
    public function edit(User $tenant) {}
    public function store(Request $request, User $tenant) {}
    public function update(Request $request, User $tenant) {}
    public function delete(User $tenant) {}
}
