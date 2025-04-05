<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, int $tenant_id) {}
    public function destroy(int $tenant_id) {}
}
