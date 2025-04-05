<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request) {}
    public function update(Request $request, int $tenant_id) {}
    public function delete(int $tenant_id) {}
}
