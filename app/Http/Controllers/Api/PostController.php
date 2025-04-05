<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(int $tenant_id) {}
    public function store(Request $request, int $tenant_id) {}
    public function show(int $id, int $tenant_id) {}
    public function update(Request $request, int $tenant_id) {}
    public function destroy(int $tenant_id) {}
}
